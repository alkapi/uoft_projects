#include <stdio.h>
#include <stdlib.h>

void swap(int *, int *);
void split_time(long, int *, int *, int *);
int *find_largest(int[], int);
void find_two_largest(int[], int, int *, int *);

int main(void) {
	int hr, min, sec, second_largest, mylargest;
	int arr[3] = {3, 2, 1};
	split_time(123456, &hr, &min, &sec);
	printf("%d hours, %d min, %d sec\n", hr, min, sec);
	int *largest = find_largest(arr, 3);
	printf("largest is: %d\n", *largest);
	
	find_two_largest(arr, 3, &mylargest, &second_largest);
	printf("mylargest is: %d\n", mylargest);
	printf("second largest is: %d\n", second_largest); 
	return 0;
}

void swap(int *p, int *q) {
	int *temp = p;
	*p = *q;
	*q = *temp;
}

void split_time(long total_sec, int *hr, int *min, int *sec) {
	*hr = (int)total_sec/3600;
	*min = (int)total_sec/60 - (60 * (*hr));
	*sec = (int)total_sec - (3600 * (*hr)) - (60 * (*min));
}

int *find_largest(int arr[], int n) {
	if (n <= 0)
		return NULL;
	int *largest = arr;
	if (n > 1) {
		int i;
		for (i = 1; i < n; i++) {
			if (arr[i] > *largest) 
				largest = &arr[i];
		}
	}
	return largest;	
}

void find_two_largest(int arr[], int n, int *largest, int *second_largest) {
	int *mylargest = find_largest(arr, n);
	if (mylargest == NULL) {
		return;
	}
	*largest = *mylargest;
	if (n == 1) 
		return;
	int i = 0;
	printf("in ftl, largest is: %d\n", *largest);
	if (mylargest == &arr[i]) 
		i++;
	*second_largest = arr[i];
	printf("in ftl, sec_larg is: %d\n", *second_largest);
	int j;
	for (j = (i + 1); j < n; j++) {
		printf("in ftl, j is : %d\n", j);
		if ((mylargest != &arr[j]) && (arr[j] > *second_largest)) {
			printf("in ftl, arr[j] is %d\n", arr[j]);
			*second_largest = arr[j];		
		}
	}

}


