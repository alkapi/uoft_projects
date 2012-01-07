#include <stdio.h>

int sum_array(int *, int);
void store_zeros(int *, int);
void find_two_largest(const int *, int, int *, int *);

int main(void) {
	int largest, second_largest;
	int a[] = {3, 2, 1};
	int sum = sum_array(a, 3);
	printf("sum is: %d\n", sum);
	store_zeros(a, 3);
	int new_sum = sum_array(a, 3);
	printf("new sum is: %d\n", new_sum); 
	int arr[] = {1, 5, 4, 3};
	find_two_largest(arr, 4, &largest, &second_largest);
	printf("largest is %d and second_largest is %d\n", largest, second_largest);
	return 0;
}

int sum_array(int *a, int n) {
	int sum = 0;
	int *p = a;
	while (p < &a[n])
		sum += *p++;
	return sum;
}

void store_zeros(int *a, int n) {
	int *p;
	for (p = a; p < a + n; p++)
		*p = 0;
}

void find_two_largest(const int *a, int n, int *largest, int *second_largest) {
	if (*a > *(a + 1)) {
		*largest = *a;
		*second_largest = *(a + 1);
	} else {
		*largest = *(a + 1);
		*second_largest = *a;
	}

	const int *p;

	for (p = a + 2; p < a + n; p++) {
		if (*p > *largest) {
			*second_largest = *largest;
			*largest = *p;
		} else if (*p > *second_largest) {
			*second_largest = *p;
		}
	}		
}

