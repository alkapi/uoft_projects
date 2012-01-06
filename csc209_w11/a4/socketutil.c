#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>
#include <sys/types.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <netdb.h>
#include "socketutil.h"

static char *extractline(char *p, int size);
static char *memnewline(char *p, int size);  /* finds \r _or_ \n */


int connect_to_server(char *host, int port)
{
    struct hostent *hp;
    int serverfd;
    struct sockaddr_in r;

    if ((hp = gethostbyname(host)) == NULL) {
	fprintf(stderr, "%s: no such host\n", host);
	exit(1);
    }
    if (hp->h_addr_list[0] == NULL || hp->h_addrtype != AF_INET) {
	fprintf(stderr, "%s: not an internet protocol host name\n", host);
	exit(1);
    }

    if ((serverfd = socket(AF_INET, SOCK_STREAM, 0)) < 0) {
        perror("socket");
        exit(1);
    }

    memset(&r, '\0', sizeof r);
    r.sin_family = AF_INET;
    memcpy(&r.sin_addr, hp->h_addr_list[0], hp->h_length);
    r.sin_port = htons(port);

    if (connect(serverfd, (struct sockaddr *)&r, sizeof r) < 0) {
        perror("connect");
        exit(1);
    }

    return(serverfd);
}


static char buf[300], *nextpos;  /* leftover data and where we are in it */
static int bytes_in_buf = 0;  /* how many data bytes there are after nextpos */

char *myreadline(int fd)
{
    int count;
    extern char *extractline(char *p, int size);  /* returns ptr to after */

    /* move the leftover data to the beginning of buf */
    if (bytes_in_buf && nextpos)
	memmove(buf, nextpos, bytes_in_buf);

    /* If we've already got another whole line, return it without a read() */
    if ((nextpos = extractline(buf, bytes_in_buf))) {
	bytes_in_buf -= (nextpos - buf);
	return(buf);
    }

    /* ok, try a read() */
    if ((count = read(fd, buf + bytes_in_buf, sizeof buf - bytes_in_buf - 2))
	    < 0) {
	perror("read");
	exit(1);
    }
    if (count == 0) {
	printf("Server shut down\n");
	exit(0);
    }
    bytes_in_buf += count;

    /* So, _now_ do we have a whole line? */
    if ((nextpos = extractline(buf, bytes_in_buf))) {
	bytes_in_buf -= (nextpos - buf);
	return(buf);
    }

    /*
     * Don't do another read(), to avoid the possibility of blocking.
     * If the caller wants a line no matter what, they should call
     * myreadline() in a loop.
     * However, if the buffer is full, we should call this a line.
     */
    if (bytes_in_buf + 2 >= sizeof buf) {
	buf[bytes_in_buf] = '\0';
	bytes_in_buf  = 0;
	return(buf);
    } else {
	return(NULL);
    }
}


int myreadline_waiting()
{
    return(!!memnewline((bytes_in_buf && nextpos) ? nextpos : buf,
                        bytes_in_buf));
}


static char *extractline(char *p, int size)
	/* returns pointer to string after, or NULL if there isn't an entire
	 * line there */
{
    char *nl = memnewline(p, size);
    if (!nl)
	return(NULL);

    /*
     * There are three cases: either this is a lone \r, a lone \n, or a CRLF.
     */
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


static char *memnewline(char *p, int size)  /* finds \r _or_ \n */
	/* This is like min(memchr(p, '\r'), memchr(p, '\n')) */
	/* It is named after memchr().  There's no memcspn(). */
{
    while (size > 0) {
	if (*p == '\r' || *p == '\n')
	    return(p);
	p++;
	size--;
    }
    return(NULL);
}
