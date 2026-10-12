import { Injectable, inject } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { BehaviorSubject, first, Observable, ReplaySubject, tap } from 'rxjs';
import { finalize } from 'rxjs/operators';
import { CountedAndPaginated } from '../interface/response/counted-and-paginated';
import { FilterService } from './filter.service';
import { Employee } from '../interface/employee';


@Injectable({
  providedIn: 'root',
})
export class EmployeeService {
  private readonly http = inject(HttpClient);
  private readonly filterService = inject(FilterService);

  private readonly employeeListSubject = new BehaviorSubject<CountedAndPaginated<Employee>>({
    items: [],
    pagination: {
      currentPage: 1,
      pageSize: 10,
      thisPageSize: 0,
      total: 0,
    },
  });
  public readonly employeeList$ = this.employeeListSubject.asObservable();

  private readonly loadingSubject = new ReplaySubject<boolean>(1);
  public readonly loading$ = this.loadingSubject.asObservable();

  public create(payload: Employee): Observable<Employee> {
    console.log(payload);
    return this.http.post<Employee>('/api/employee', payload);
  }

  public update(uuid: string, payload: Employee): Observable<Employee> {
    return this.http.put<Employee>(`/api/employee/${uuid}`, payload);
  }

  public get(uuid: string): Observable<Employee> {
    return this.http.get<Employee>(`/api/employee/${uuid}`);
  }

  public delete(uuid: string): Observable<any> {
    return this.http.delete(`/api/employee/${uuid}`).pipe(first());
  }

  public loadList(
    page: number,
    pageSize: number,
    filters: object,
    sort?: { sortBy?: string; sortOrder?: string },
  ): void {
    const sortBy = sort?.sortBy ?? 'createdAt';
    const sortOrder = sort?.sortOrder ?? 'ASC';

    let params = new HttpParams()
      .set('page', page + 1)
      .set('pageSize', pageSize)
      .set('sortBy', sortBy)
      .set('sortOrder', sortOrder);

    const parsedFilters = this.filterService.parseFiltersToObject(filters);
    for (const [key, value] of Object.entries(parsedFilters)) {
      if (value !== null && value !== undefined) {
        params = params.set(`filter[${key}]`, String(value));
      }
    }

    this.loadingSubject.next(true);

    this.http
      .get<CountedAndPaginated<Employee>>('/api/employee-list', { params })
      .pipe(finalize(() => this.loadingSubject.next(false)))
      .subscribe({
        next: (result) => {
          this.employeeListSubject.next(result);
        },
        error: (err) => {
          console.error('Błąd podczas pobierania listy pracowników:', err);
        },
      });
  }
}
