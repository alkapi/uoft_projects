/*
 * connect_to_server() exits on error, else returns fd of socket to server
 */
extern int connect_to_server(char *host, int port);

/*
 * myreadline() does a read(); it returns only one line, or returns NULL
 * if we don't have an entire line yet (in which case you want to loop around
 * and call myreadline() again the next time select() says that something
 * happened).
 * myreadline() exits upon error or server shutdown.
 */
extern char *myreadline(int fd);

/*
 * myreadline_waiting() tells us whether there is data waiting even if there
 * isn't any socket activity.  This is necessary because the previous read()
 * in a myreadline() might have read multiple lines at once.  So even if
 * select() says there's nothing to read, you should call myreadline_waiting()
 * for a second opinion.  Actually you should call it first, before even
 * calling select().  If myreadline_waiting() returns non-zero, you should go
 * ahead and call myreadline().
 */
extern int myreadline_waiting();
