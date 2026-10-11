import { Component, inject } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { InputTextModule } from 'primeng/inputtext';
import { ButtonModule } from 'primeng/button';
import { CardModule } from 'primeng/card';
import { CommonModule } from '@angular/common';
import { EmployeeService } from '../../../shared/services/employee.service';
import { Router } from '@angular/router';
import { ToastModule } from 'primeng/toast';
import { MessageService } from 'primeng/api';

@Component({
  selector: 'app-employee-add',
  standalone: true,
  imports: [
    CommonModule,
    ReactiveFormsModule,
    InputTextModule,
    ButtonModule,
    CardModule,
    ToastModule,
  ],
  providers: [MessageService],
  templateUrl: './employee-add.component.html',
  styleUrl: './employee-add.component.scss',
})
export class EmployeeAddComponent {
  // Wstrzykiwanie zależności za pomocą inject()
  private readonly fb = inject(FormBuilder);
  private readonly router = inject(Router);
  private readonly messageService = inject(MessageService);
  private readonly employeeService = inject(EmployeeService); // <-- TUTAJ BYŁ BRAK

  employeeForm: FormGroup;
  processingRequest = false;

  constructor() {
    this.employeeForm = this.fb.group({
      firstName: ['', Validators.required],
      lastName: ['', Validators.required],
      role: ['', Validators.required],
      email: ['', Validators.required]
    });
  }

  onSubmit() {
    console.log('aaaa');

    if (this.employeeForm.invalid) {
      this.employeeForm.markAllAsTouched();
      this.messageService.add({
        severity: 'warn',
        summary: 'Walidacja',
        detail: 'Wypełnij wszystkie wymagane pola',
        life: 3000,
      });
      return;
    }

    this.processingRequest = true;

    this.employeeService.create(this.employeeForm.value).subscribe({
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
