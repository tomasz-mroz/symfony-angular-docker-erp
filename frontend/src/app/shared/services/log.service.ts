import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { first, ReplaySubject } from 'rxjs';

import { CountedAndPaginated } from '../interface/response/counted-and-paginated';
import { Log } from '../interface/log';

export type LogEntityType = 'employee';

@Injectable({ providedIn: 'root' })
export class LogService {
  private readonly http = inject(HttpClient);

  private readonly logListSubject = new ReplaySubject<CountedAndPaginated<Log>>(1);

  private readonly endpointMap: Record<LogEntityType, (uuid: string) => string> = {
    employee: (uuid) => `/api/employee/${uuid}/log`,
  };

  getLogListSubject(): ReplaySubject<CountedAndPaginated<Log>> {
    return this.logListSubject;
  }

  loadLogList(entityType: LogEntityType, uuid: string, page: number, pageSize: number): void {
    const buildUrl = this.endpointMap[entityType];
    if (!buildUrl) {
      console.warn(`[LogService] Brak endpointu dla encji: ${entityType}`);
      this.resetSubject();
      return;
    }

    const url = `${buildUrl(uuid)}?page=${page + 1}&pageSize=${pageSize}`;

    this.http
      .get<CountedAndPaginated<Log>>(url)
      .pipe(first())
      .subscribe({
        next: (result) => this.logListSubject.next(result),
        error: () => this.resetSubject(),
      });
  }

  private resetSubject(): void {
    this.logListSubject.next({
      items: [],
      pagination: { total: 0, pageSize: 0, thisPageSize: 0, currentPage: 1 },
    });
  }
}
