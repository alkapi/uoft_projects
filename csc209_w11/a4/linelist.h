/*
 * data structure maintaining a bunch of lines for the Terrible Drawing Program
 */

extern void linelist_init();
extern void linelist_clear();
extern int linelist_add(int x1, int y1, int x2, int y2);


/* iterator class */
struct linelist_element {
    int x1, y1, x2, y2;
    struct linelist_element *next;
};
typedef struct linelist_element *LINELIST_ITER;
#define linelist_null() ((LINELIST_ITER)NULL)
extern LINELIST_ITER linelist_next(LINELIST_ITER i);
#define LINELIST_ISNULL(i) ((i) == NULL)
#define LINELIST_X1(i) ((i)->x1)
#define LINELIST_Y1(i) ((i)->y1)
#define LINELIST_X2(i) ((i)->x2)
#define LINELIST_Y2(i) ((i)->y2)
