#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <sys/socket.h>
#include "linelist.h"
#include <sys/types.h>
#include <ctype.h>
#include <netinet/in.h>
#include <unistd.h>
#include <arpa/inet.h>
#include <sys/signal.h>
#include <errno.h>
#include <time.h>
#include <sys/time.h>

/* tgdpserver contains code cited from muffinman.c,
 * linelist.c and socketutil.c */

/* tgdpserver relays line and clear commands sent by all 
 * connected clients to a newly connected client. 
 * It sends lines drawn by one client to all the others,
 * and does the same for the "clear" command. */

/* Default port to use */
int port = 7000;

/* Keep track of clients info */
struct client {
	int fd;
	struct in_addr ipaddr;
	char *nextpos;
	char buf[1024];
	int bytes_in_buf;
	struct client *next;
} *head = NULL;


/* Establish connection */
static int listenfd;

/* client linked list functions cited from muffinman.c */
void addclient(int fd, struct in_addr addr);
void removeclient(int fd);
void broadcast(int fd);

/* cited from socketutil.c */
char *tgdp_extractline(char *p, int size);
char *tgdp_memnewline(char *p, int size);
char *tgdp_myreadline(struct client *p);

/* Given a client node, and a line returned from tgdp_myreadline
 * either add the line to the linked list of lines, or clear
 * the linked list. Relay this command to all other clients. */
void process_client(struct client *p, char *buf) {
	int x1, y1, x2, y2;
	if (strncmp(buf, "line ", 5) == 0) {
		if (sscanf(buf+5, "%d%d%d%d", &x1, &y1, &x2, &y2) != 4) {
			perror("sscanf");
            exit(1);
         }
         linelist_add(x1, y1, x2, y2);
		 struct client *ptr;
		 char abuf[256];
		 sprintf(abuf, "line %d %d %d %d\r\n", x1, y1, x2, y2);
         for (ptr = head; ptr; ptr = ptr->next) {
	         if (ptr->fd != p->fd) {
               write(ptr->fd, abuf, strlen(abuf) - 1);
             }
		 }
	} else if (strncmp(buf, "clear", 5) == 0) {
		linelist_clear();
		struct client *ptr;
		/* relay command to all other clients */
		for (ptr = head; ptr; ptr = ptr->next) {
			if (ptr->fd != p->fd) {
				write(ptr->fd, "clear\r\n", 7);
			}	
		}
	}
}


int main(int argc, char **argv) {
	int opt;
	struct client *p;
	linelist_init(); /* Create linked list to store lines*/

	extern void bindandlisten(), newconnection(); 
	//extern char *tgdp_myreadline(struct client *p);
	extern int tgdp_myreadline_waiting(struct client *p);
	extern void whatsup(struct client *p, fd_set fdlist);

	/* Parse command line arguments */
	while ((opt = getopt(argc, argv, "p:")) != EOF) {
	if (opt == 'p') {
		if ((port = atoi(optarg)) == 0) {
		fprintf(stderr, "%s non-numeric port \"number\"\n", argv[0]);
		return(1);
		}
	} else {
		fprintf(stderr, "usage: %s [-p port]\n", argv[0]);
		return(1);
	}
	}

	if (argv[optind] != NULL) {
		fprintf(stderr, "usage: %s [-p port]\n", argv[0]);
		return(1);
	}
	
	bindandlisten(); /* Call socket, bind and listen on listenfd */

	while(1) {
		fd_set fdlist;
		int maxfd = listenfd;
		FD_ZERO(&fdlist);
		FD_SET(listenfd, &fdlist); 
		

		/* Set each bit, fd in client list, in the fdlist bit mask */
		for (p = head; p; p = p->next) {
			FD_SET(p->fd, &fdlist);
			if (p->fd > maxfd)
				maxfd = p->fd; /* Find the maxfd */
		}

		/* Indicate which of the fds are ready for read activity */
		if (select(maxfd + 1, &fdlist, NULL, NULL, NULL) < 0) {
			perror("select");
		} else {
			for (p = head; p; p = p->next) {
				if (FD_ISSET(p->fd, &fdlist)) {
					break;
				}
			}
			if (p) {
				whatsup(p, fdlist);
				while(tgdp_myreadline_waiting(p)) {
					whatsup(p, fdlist);
				}
			}
			if (FD_ISSET(listenfd, &fdlist)) {
				newconnection();
			}
		}
	}

	return(0);
}

/* Retrieves a line from a ready client, and removes the 
 * client if EOF is reached. Otherwise it processes the l
 * line and relays it all other clients. */
void whatsup(struct client *p, fd_set fdlist){
	// Call my readline
	char *buf;
	if ((buf = tgdp_myreadline(p)) == NULL) {
		FD_CLR(p->fd, &fdlist);
		removeclient(p->fd);
	}
	else {
		process_client(p, buf);
	}
	// Check if its null, if it is call remove and FD_CLR
	// Else, parse the string returned my read line (line or clear)
	// Deal with it, write to everyone other then the guy who called
	// this method (save his fd before starting to know who he is)
}

/* Establish transport end point, store details of client,
 * bind network address with a socket identifier, and wait
 * for connections. */
void bindandlisten() {
	struct sockaddr_in r;

	if ((listenfd = socket(AF_INET, SOCK_STREAM, 0)) < 0) {
		perror("socket");
		exit(1);
	}

	memset(&r, '\0', sizeof r);
	r.sin_family = AF_INET;
	r.sin_port = htons(port);
	r.sin_addr.s_addr = INADDR_ANY;

	if (bind(listenfd, (struct sockaddr *)&r, sizeof r)) {
		perror("bind");
		exit(1);
	}

	if (listen(listenfd, 5)) {
		perror("listen");
		exit(1);
	}
}

/* Upon receiving a connect request from a client, create
 * socket for specific communication. Broadcast "TGDP\r\n" 
 * to client. Add client to list of clients that are 
 * connected to server, and broadcast set of lines
 * from other clients. */
void newconnection()  /* accept connection, update linked list */
{
    int fd;
    struct sockaddr_in r;
    socklen_t socklen = sizeof r;

    if ((fd = accept(listenfd, (struct sockaddr *)&r, &socklen)) < 0) {
		perror("accept");
    } else {
		printf("connection from %s\n", inet_ntoa(r.sin_addr));
		addclient(fd, r.sin_addr); /* add newly made client to list */
		broadcast(fd);
	} 
}

/* Add a newly connected client to the list of clients. */
void addclient(int fd, struct in_addr addr) {
	struct client *p = (struct client *)malloc(sizeof(struct client));
	if (!p) {
		fprintf(stderr, "out of memory!\n");
		exit(1);
	}
	printf("Adding client %s\n", inet_ntoa(addr));
	p->fd = fd;
	p->ipaddr = addr;
	p->bytes_in_buf = 0;
	p->next = head;
	head = p;
}

/* Remove a client that has disconnected from server */
void removeclient(int fd) {
	struct client **p;
	for (p = &head; *p && (*p)->fd != fd; p = &(*p)->next)
		;
	if (*p) {
		struct client *t = (*p)->next;
		printf("Removing client %s\n", inet_ntoa((*p)->ipaddr));
		close(fd);
		free((char *)*p);
		*p = t;
	} else {
		fprintf(stderr, "Trying to remove fd %d, but I don't "
			"know about it\n", fd);
	}
}

/* Returns pointer to string after, or NULL if there is not an
 * entire line */
char *tgdp_extractline(char *p, int size) {
	char *nl = tgdp_memnewline(p, size);
	if (!nl) /* Network newline not found */
		return(NULL);

	if (*nl == '\r' && nl - p < size && nl[1] == '\n') {
		/* CRLF */
		*nl = '\0';
		return(nl + 2);
	} else {
		/* lone \n or \r */
		*nl = '\0';
		return(nl + 1);
	}
}
		
/* Finds \r or \n in client struct member buf */
char *tgdp_memnewline(char *p, int size) {
	while (size > 0) {
		if (*p == '\r' || *p == '\n') 
			return p;
		p++;
		size--;
	}
	return(NULL);
}

/* Broadcast a message to every client */
void broadcast(int fd) {
	// Send the greeting.
	// Iterate over your line list, write all the lines to clients
	write(fd, "TGDP\r\n", 6);
	char buff[300];
	LINELIST_ITER i = linelist_null();
	while ((i = linelist_next(i)), !LINELIST_ISNULL(i)) {
		sprintf(buff, "line %d %d %d %d\r\n", LINELIST_X1(i), LINELIST_Y1(i), LINELIST_X2(i), LINELIST_Y2(i));
		write(fd, buff, strlen(buff));
	}
}

/* Read line from clients */
char *tgdp_myreadline(struct client *p)
{
    int count;
    extern char *tgdp_extractline(char *p, int size);  /* returns ptr to after */

    /* move the leftover data to the beginning of buf */
    if (p->bytes_in_buf && p->nextpos) {
		memmove(p->buf, p->nextpos, p->bytes_in_buf);
	}
    /* If we've already got another whole line, return it without a read() */
    if ((p->nextpos = tgdp_extractline(p->buf, p->bytes_in_buf))) {
		p->bytes_in_buf -= (p->nextpos - p->buf);
		return(p->buf);
    }

    /* ok, try a read() */
    if ((count = read(p->fd, p->buf + p->bytes_in_buf, sizeof p->buf - p->bytes_in_buf - 2)) < 0) {
		perror("read");
		exit(1);
    }
    if (count == 0) {
		/* Client shut down */
	  	printf("Disconnect from %s\n", inet_ntoa(p->ipaddr));
		return(NULL);
    }
    p->bytes_in_buf += count;

    /* So, _now_ do we have a whole line? */
    if ((p->nextpos = tgdp_extractline(p->buf, p->bytes_in_buf))) {
		p->bytes_in_buf -= (p->nextpos - p->buf);
		return(p->buf);
    }

    /*
     * Don't do another read(), to avoid the possibility of blocking.
     * If the caller wants a line no matter what, they should call
     * myreadline() in a loop.
     * However, if the buffer is full, we should call this a line.
     */
    if (p->bytes_in_buf + 2 >= sizeof p->buf) {
		p->buf[p->bytes_in_buf] = '\0';
		p->bytes_in_buf  = 0;
		return(p->buf);
    } else {
		return(NULL);
    }
}

/* Ensures that myreadline will not block. */
int tgdp_myreadline_waiting(struct client *p) {

    return (!!tgdp_memnewline((p->bytes_in_buf && p->nextpos) ? p->nextpos : p->buf,
                        p->bytes_in_buf));
}
