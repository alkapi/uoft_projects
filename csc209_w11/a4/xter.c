#include <stdio.h>
#include <stdlib.h>
#include "linelist.h"
#include "xwin.h"
#include "socketutil.h"
#include <string.h>
#include <unistd.h>

/* xter is a drawing program which is also a client. It 
 * accepts line and clear commands from other clients,
 * and draws these lines which are sent via the server. */

inline int max(int a, int b)
{ return a > b ? a : b; }

/* Processes a line sent from the server. Updates linked
 * list of lines. */
void process_server(char *buf, int x1, int y1, int x2, int y2) { 
	if (strncmp(buf, "line ", 5) == 0) {
		if (sscanf(buf+5, "%d%d%d%d", &x1, &y1, &x2, &y2) != 4) {
			perror("sscanf");
            exit(1);
		}
		linelist_add(x1, y1, x2, y2);
		xwin_draw_line(x1, y1, x2, y2);
	} else if (strncmp(buf, "clear", 5) == 0) {
		linelist_clear();
		xwin_redraw_all();
    }
}

int main(int argc, char **argv)
{
	int port;

	/* Ensure correct number of command-line arguments are provided */
	if (argc < 2 || argc > 3) {
		fprintf(stderr, "usage: %s hostname [port]\n", argv[0]);
		exit(1);
	}
	if (argc < 3) { 
		port = 7000; 
	} else { 
		if ((port = atoi(argv[2])) == 0) {
			fprintf(stderr, "usage: %s hostname [port]\n", argv[0]); 
			exit(1);
		}
	}
	if (port <= 1024) { /* Ensure valid port is obtained */
		fprintf(stderr, "Port must be greater than 1024\n");
		exit(1);
	} 
	char *hostname = argv[1];
	char *buf;
	int serverfd = connect_to_server(hostname, port);

	/* Read a line from the server and make sure it is TGDP */
	while ((buf = myreadline(serverfd)) == NULL) 
			;

	if (strncmp(buf, "TGDP", 4) != 0) {
		fprintf(stderr, "Not a TGDP server\n");
		exit(1);
	}		

	char *err;
	XWIN_EVENT ev;

	linelist_init();
    
	err = xwin_init("Terrible Drawing Program");
	if (err) {
		fprintf(stderr, "xter: %s\n", err);
		return(1);
	}

	int x1, y1, x2, y2;

	while (1) 
	{

	/* Guarantee that neither xwin_event nor myreadline_waiting block */
	while (xwin_waiting()) {
		ev = xwin_event();

		/* Process a UI event */
		if (XW_TYPE(ev) == XWIN_KEY && XW_KEY(ev) == 'q') {
			return(0);  /* exiting frees everything, closes the window, etc */
		} else if (XW_TYPE(ev) == XWIN_KEY && XW_KEY(ev) == 'c') {
			linelist_clear();
			xwin_redraw_all();
			write(serverfd, "clear\r\n", 7);
		} else if (XW_TYPE(ev) == XWIN_LINE) {
			linelist_add(XW_X1(ev), XW_Y1(ev), XW_X2(ev), XW_Y2(ev));
			xwin_draw_line(XW_X1(ev), XW_Y1(ev), XW_X2(ev), XW_Y2(ev));
			char sbuf[256];
			sprintf(sbuf, "line %d %d %d "
				"%d\r\n", XW_X1(ev), XW_Y1(ev), XW_X2(ev), XW_Y2(ev));
			write(serverfd, sbuf, strlen(sbuf));
		}
	}
	
	while (myreadline_waiting()) {
		if ((buf = myreadline(serverfd)) != NULL) { 
			/* Process command from server */
			process_server(buf, x1, y1, x2, y2);
		}
	}

	int xwinfd = xwin_fd();

	int maxfd = max(serverfd, xwinfd);
	fd_set readset;
	FD_ZERO(&readset);
	FD_SET(serverfd, &readset);
	FD_SET(xwinfd, &readset);

	if (select(maxfd + 1, &readset, NULL, NULL, NULL) < 0) {
		perror("select");
		exit(1);
	}

	/* Determine which fd has data ready to be processed */
	if (FD_ISSET(serverfd, &readset)) {
		while ((buf = myreadline(serverfd)) == NULL)
			;
		process_server(buf, x1, y1, x2, y2);
	}
	if (FD_ISSET(xwinfd, &readset)) {
		ev = xwin_event();
		if (XW_TYPE(ev) == XWIN_KEY && XW_KEY(ev) == 'q') {
			return(0);  /* exiting frees everything, closes the window, etc */					
		} else if (XW_TYPE(ev) == XWIN_KEY && XW_KEY(ev) == 'c') {
			linelist_clear();
			xwin_redraw_all();
			write(serverfd, "clear\r\n", 7);
		} else if (XW_TYPE(ev) == XWIN_LINE) {
			linelist_add(XW_X1(ev), XW_Y1(ev), XW_X2(ev), XW_Y2(ev));
			char abuf[256];
			sprintf(abuf, "line %d %d %d "
				"%d\r\n", XW_X1(ev), XW_Y1(ev), XW_X2(ev), XW_Y2(ev));
			write(serverfd, abuf, strlen(abuf));
			xwin_draw_line(XW_X1(ev), XW_Y1(ev), XW_X2(ev), XW_Y2(ev));
		}
	}
}	
	
}
