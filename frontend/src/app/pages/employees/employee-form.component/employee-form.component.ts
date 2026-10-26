import { Component, OnInit, Input, Output, EventEmitter, inject } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { InputTextModule } from 'primeng/inputtext';
import { ButtonModule } from 'primeng/button';
import { CardModule } from 'primeng/card';
import { CommonModule } from '@angular/common';
import { EmployeeService } from '../../../shared/services/employee.service';
import { Router } from '@angular/router';
import { ToastModule } from 'primeng/toast';
import { MessageService } from 'primeng/api';
import { Employee } from '../../../shared/interface/employee';

@Component({
  selector: 'app-employee-form',
  standalone: true,
  imports: [
    CommonModule,
    ReactiveFormsModule,
    InputTextModule,
    ButtonModule,
    CardModule,
    ToastModule,
  ],
  templateUrl: './employee-form.component.html',
  styleUrl: './employee-form.component.scss',
})
export class EmployeeFormComponent implements OnInit {
  private readonly fb = inject(FormBuilder);
  private readonly router = inject(Router);
  private readonly messageService = inject(MessageService);
  private readonly employeeService = inject(EmployeeService);

  @Input() employeeData?: Employee | null;
  @Output() formClosed = new EventEmitter<void>();

  employeeForm!: FormGroup;
  processingRequest = false;

  get isEditMode(): boolean {
    return !!this.employeeData && !!this.employeeData.uuid;
  }

  ngOnInit(): void {
    this.initForm();
  }

  private initForm(): void {
    this.employeeForm = this.fb.group({
      firstName: [this.employeeData?.firstName ?? '', Validators.required],
      lastName: [this.employeeData?.lastName ?? '', Validators.required],
      role: [this.employeeData?.role ?? '', Validators.required],
      email: [this.employeeData?.email ?? '', [Validators.required, Validators.email]],
    });
  }

  onSubmit(): void {
    if (this.employeeForm.invalid) {
      this.employeeForm.markAllAsTouched();
      this.messageService.add({
        severity: 'warn',
        summary: 'Walidacja',
        detail: 'Wypełnij poprawnie wszystkie wymagane pola',
        life: 3000,
      });
      return;
    }

    this.processingRequest = true;
    const formValues = this.employeeForm.value;

    if (this.isEditMode && this.employeeData?.uuid) {
      this.employeeService.update(this.employeeData.uuid, formValues).subscribe({
        next: () => {
          this.messageService.add({
            severity: 'success',
            summary: 'Sukces',
            detail: 'Dane pracownika zostały zaktualizowane',
            life: 2000,
          });
          this.formClosed.emit();
        },
        error: () => {
          this.processingRequest = false;
          this.messageService.add({
            severity: 'error',
            summary: 'Błąd',
            detail: 'Nie udało się zaktualizować danych',
            life: 3000,
          });
        },
      });
    } else {
      this.employeeService.create(formValues).subscribe({
        next: () => {
          this.messageService.add({
            severity: 'success',
            summary: 'Sukces',
            detail: 'Pracownik został dodany',
            life: 2000,
          });
          this.router.navigate(['/pracownicy']);
        },
        error: () => {
          this.processingRequest = false;
          this.messageService.add({
            severity: 'error',
            summary: 'Błąd',
            detail: 'Błąd podczas dodawania',
            life: 3000,
          });
        },
      });
    }
  }

  onCancel(): void {
    if (this.isEditMode) {
      this.formClosed.emit();
    } else {
      this.router.navigate(['/pracownicy']);
    }
  }
}
