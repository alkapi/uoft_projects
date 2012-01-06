CFLAGS = -Wall -g -I /usr/X11R6/include
OFILES = xter.o xwin.o linelist.o

xter: $(OFILES)
	gcc -Wall -g -o xter $(OFILES) -L/usr/X11R6/lib -lX11

clean:
	rm -f $(OFILES) xter

xter.o: xter.c xwin.h linelist.h
xwin.o: xwin.c xwin.h linelist.h
linelist.o: linelist.c linelist.h
