/*
 * data structure maintaining a bunch of lines for the Terrible Drawing Program
 */

#include <stdlib.h>
#include "linelist.h"


/* see struct linelist_element in linelist.h */

static struct linelist_element *top;


void linelist_init()
{
    top = NULL;
}


void linelist_clear()
{
    struct linelist_element *p = top;
    top = NULL;
    while (p) {
	struct linelist_element *that = p;
	p = p->next;
	free((void *)that);
    }
}


int linelist_add(int x1, int y1, int x2, int y2)
{
    struct linelist_element *p = (struct linelist_element *)malloc(sizeof(struct linelist_element));
    if (p == NULL)
	return(-1);
    p->x1 = x1;
    p->y1 = y1;
    p->x2 = x2;
    p->y2 = y2;
    p->next = top;
    top = p;
    return(0);
}


struct linelist_element *linelist_next(LINELIST_ITER i)
{
    if (i == NULL)
	return(top);
    else
	return(i->next);
}
