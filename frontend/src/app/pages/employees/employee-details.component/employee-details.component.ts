import { Component, OnInit, OnDestroy, inject, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ActivatedRoute } from '@angular/router';
import { HttpErrorResponse } from '@angular/common/http';
import { ReplaySubject, takeUntil } from 'rxjs';

import { BreadcrumbComponent } from '../../../shared/components/breadcrumb.component/breadcrumb.component';
import { LogListComponent } from '../../logs/log-list.component/log-list.component';
import { EmployeeDetailsViewComponent } from '../employee-details-view.component/employee-details-view.component';
import { EmployeeFormComponent } from '../employee-form.component/employee-form.component';

import { EmployeeService } from '../../../shared/services/employee.service';

import { TabsModule } from 'primeng/tabs';
import { ToastModule } from 'primeng/toast';
import { ConfirmDialogModule } from 'primeng/confirmdialog';
import { MessageService, ConfirmationService } from 'primeng/api';
import { DialogService } from 'primeng/dynamicdialog';

import { Employee } from '../../../shared/interface/employee';

@Component({
  selector: 'app-employee-details',
  standalone: true,
  imports: [
    CommonModule,
    TabsModule,
    ToastModule,
    ConfirmDialogModule,
    BreadcrumbComponent,
    EmployeeDetailsViewComponent,
    EmployeeFormComponent,
    LogListComponent,
  ],
  providers: [MessageService, ConfirmationService, DialogService],
  templateUrl: './employee-details.component.html',
  styleUrls: ['./employee-details.component.scss'],
})
export class EmployeeDetailsComponent implements OnInit, OnDestroy {
  private readonly route = inject(ActivatedRoute);
  private readonly employeeService = inject(EmployeeService);
  private readonly messageService = inject(MessageService);

  private readonly destroyed$ = new ReplaySubject<boolean>(1);

  readonly modify = signal(false);
  readonly employee = new ReplaySubject<Employee>(1);

  uuid: string | null = null;

  ngOnInit(): void {
    this.route.params.pipe(takeUntil(this.destroyed$)).subscribe((params) => {
      this.uuid = params['id'] ?? null;
      this.loadEmployee();
    });
  }

  ngOnDestroy(): void {
    this.destroyed$.next(true);
    this.destroyed$.complete();
  }

  loadEmployee(): void {
    if (!this.uuid) {
      return;
    }

    this.employeeService
      .get(this.uuid)
      .pipe(takeUntil(this.destroyed$))
      .subscribe({
        next: (employee: Employee) => {
          this.employee.next(employee);
        },
        error: (error: HttpErrorResponse) => {
          this.messageService.add({
            severity: 'error',
            summary: 'Nie udało się pobrać danych pracownika',
            detail: error.status?.toString() ?? '',
            life: 2000,
          });
        },
      });
  }

  onModifyClosed(): void {
    this.modify.set(false);
    this.loadEmployee();
  }
}
