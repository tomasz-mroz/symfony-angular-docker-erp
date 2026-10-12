import {
  Component,
  EventEmitter,
  input, // <--- Zaimportuj input
  Output,
  inject,
} from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { ReplaySubject, takeUntil } from 'rxjs';

import { ButtonModule } from 'primeng/button';
import { CardModule } from 'primeng/card';
import { MessageService, ConfirmationService } from 'primeng/api';
import { ConfirmDialogModule } from 'primeng/confirmdialog';
import { ToastModule } from 'primeng/toast';

import { DetailComponent } from '../../../shared/components/details/detail/detail.component';
import { AuthService } from '../../../shared/services/auth.service';
import { EmployeeService } from '../../../shared/services/employee.service';

import { Employee } from '../../../shared/interface/employee';

@Component({
  selector: 'app-employee-details-view',
  standalone: true,
  imports: [
    CommonModule,
    RouterModule,
    ButtonModule,
    CardModule,
    ConfirmDialogModule,
    ToastModule,
    DetailComponent,
  ],
  providers: [MessageService, ConfirmationService],
  templateUrl: './employee-details-view.component.html',
  styleUrls: ['./employee-details-view.component.scss'],
})
export class EmployeeDetailsViewComponent {
  private readonly dialogConfirmation = inject(ConfirmationService);
  private readonly messageService = inject(MessageService);
  private readonly employeeService = inject(EmployeeService);
  private readonly auth = inject(AuthService);

  private readonly destroyed$ = new ReplaySubject<boolean>(1);

  readonly employee = input<Employee | null>(null);
  @Output() modifyClicked = new EventEmitter<void>();

  ngOnDestroy(): void {
    this.destroyed$.next(true);
    this.destroyed$.complete();
  }

  modify(): void {
    this.modifyClicked.emit();
  }

  delete(): void {
    const employeeData = this.employee(); // Wywołujemy sygnał inputa
    if (!employeeData) {
      return;
    }

    this.dialogConfirmation.confirm({
      message: `Czy na pewno chcesz usunąć pracownika ${employeeData.firstName} ${employeeData.lastName}?`,
      header: 'Potwierdzenie',
      icon: 'pi pi-exclamation-triangle',
      acceptLabel: 'Tak',
      rejectLabel: 'Nie',
      accept: () => {
        this.employeeService
          .delete(employeeData.uuid)
          .pipe(takeUntil(this.destroyed$))
          .subscribe({
            next: () => {
              this.messageService.add({
                severity: 'success',
                summary: 'Pomyślnie usunięto pracownika',
                life: 2000,
              });
            },
            error: (response) => {
              this.messageService.add({
                severity: 'error',
                summary: response.error?.error?.text ?? 'Błąd',
                detail: response.status?.toString() ?? '',
                life: 2000,
              });
            },
          });
      },
    });
  }
}
