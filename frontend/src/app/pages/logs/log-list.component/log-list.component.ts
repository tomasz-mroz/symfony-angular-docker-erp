import {
  Component,
  OnDestroy,
  OnInit,
  computed,
  inject,
  input,
  signal,
  Input,
} from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { Observable, ReplaySubject, takeUntil } from 'rxjs';

import { TableModule } from 'primeng/table';
import { TooltipModule } from 'primeng/tooltip';
import { PaginatorModule, PaginatorState } from 'primeng/paginator';

import { DetailComponent } from '../../../shared/components/details/detail/detail.component';
import { LogService, LogEntityType } from '../../../shared/services/log.service';
import { AuthService } from '../../../shared/services/auth.service';

import { CountedAndPaginated } from '../../../shared/interface/response/counted-and-paginated';
import { Log, LogDiff, SimpleDiff, ArrayDiff } from '../../../shared/interface/log';
import { Employee } from '../../../shared/interface/employee';
// import { UserPermissions } from '../../../shared/interface/user-permissions';

export interface LoggableEntity {
  uuid: string;
  createdAt?: string;
}

@Component({
  selector: 'app-log-list',
  standalone: true,
  imports: [
    CommonModule,
    RouterModule,
    TableModule,
    TooltipModule,
    PaginatorModule,
    DetailComponent,
  ],
  templateUrl: './log-list.component.html',
  styleUrls: ['./log-list.component.scss'],
})
export class LogListComponent implements OnInit, OnDestroy {
  private readonly logService = inject(LogService);
  private readonly auth = inject(AuthService);

  readonly entityType = input.required<LogEntityType>();

  readonly entity = input.required<Observable<LoggableEntity>>();

  readonly logs = signal<Log[]>([]);
  readonly total = signal(0);
  readonly pageIndex = signal(0);
  readonly pageSize = signal(10);
  readonly createdAt = signal<string | undefined>(undefined);

  private readonly currentUuid = signal<string | null>(null);
  private readonly destroyed$ = new ReplaySubject<boolean>(1);

  readonly isLimitedAccess = (_diff: LogDiff): boolean => false;
  // private readonly userPermissions$ = this.auth.getUserPermissions();
  // readonly permissions = signal<UserPermissions | null>(null);

  // readonly canSeeIdImport = computed<boolean>(() => !!this.permissions()?.registration?.id_import);

  constructor() {
    // this.userPermissions$
    //   .pipe(takeUntil(this.destroyed$))
    //   .subscribe((p) => this.permissions.set(p));
  }

  ngOnInit(): void {
    this.logService
      .getLogListSubject()
      .pipe(takeUntil(this.destroyed$))
      .subscribe((result: CountedAndPaginated<Log>) => {
        this.logs.set(result.items);
        this.total.set(result.pagination.total);
      });

    this.entity()
      .pipe(takeUntil(this.destroyed$))
      .subscribe((entity) => {
        this.currentUuid.set(entity.uuid);
        this.createdAt.set(entity.createdAt);
        this.loadLogs();
      });
  }

  ngOnDestroy(): void {
    this.destroyed$.next(true);
    this.destroyed$.complete();
  }

  onPageChange(event: PaginatorState): void {
    this.pageIndex.set(event.page ?? 0);
    this.pageSize.set(event.rows ?? 10);
    this.loadLogs();
  }

  private loadLogs(): void {
    const uuid = this.currentUuid();
    if (!uuid) return;

    this.logService.loadLogList(this.entityType(), uuid, this.pageIndex(), this.pageSize());
  }

  readonly isArrayDiff = (diff: LogDiff): diff is ArrayDiff =>
    Array.isArray((diff as ArrayDiff).records);

  readonly isSimpleDiff = (diff: LogDiff): diff is SimpleDiff =>
    !Array.isArray((diff as ArrayDiff).records);

  readonly isIdDiff = (diff: LogDiff): boolean => diff.displayName === 'Identyfikator';

  // readonly isLimitedAccess = (diff: LogDiff): boolean =>
  //   this.isIdDiff(diff) && !this.canSeeIdImport();

  // ─── UTIL ──────────────────────────────────────────────────────────────
  @Input() employee!: Observable<Employee>;
  onImgError(event: Event): void {
    const target = event.target as HTMLImageElement;
    target.src = '/assets/avatar.png';
  }
}
