import { CountedPagination } from './counted-pagination';

export interface CountedAndPaginated<T> {
  items: Array<T>;
  pagination: CountedPagination;
  data?: Array<T>;
}
