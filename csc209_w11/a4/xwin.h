/*
 * X Windows stuff for the Terrible Drawing Program
 */

struct xw_event {
    enum { XWIN_NONE, XWIN_KEY, XWIN_LINE } type;
    int key, x1, y1, x2, y2;
};
typedef struct xw_event *XWIN_EVENT;
#define XW_TYPE(x) ((x)->type)
#define XW_KEY(x) ((x)->key)
#define XW_X1(x) ((x)->x1)
#define XW_Y1(x) ((x)->y1)
#define XW_X2(x) ((x)->x2)
#define XW_Y2(x) ((x)->y2)

extern char *xwin_init(char *wintitle);  /* non-NULL return value means error */
extern struct xw_event *xwin_event();
extern void xwin_redraw_all();
extern void xwin_draw_line(int x1, int y1, int x2, int y2);
extern int xwin_waiting();  /* if non-zero, xwin_event() will not block */
extern int xwin_fd();  /* return fd for use in select() */
                       /* note: call xwin_waiting() before doing a select(),
			* and if xwin_waiting() returns non-zero, don't
			* select(), just proceed with xwin_event()
			*/
