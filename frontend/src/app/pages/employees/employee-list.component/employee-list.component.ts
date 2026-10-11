import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { TableModule } from 'primeng/table';
import { InputTextModule } from 'primeng/inputtext';
import { ButtonModule } from 'primeng/button';
import { Employee } from '../../../shared/interface/employee';

@Component({
  selector: 'app-employee-list',
  standalone: true,
  imports: [CommonModule, RouterModule, TableModule, InputTextModule, ButtonModule],
  templateUrl: './employee-list.component.html',
  styleUrl: './employee-list.component.scss',
})
export class EmployeeListComponent implements OnInit {
  employees: Employee[] = [];
  loading: boolean = true;

  ngOnInit(): void {
    this.loadEmployees();
  }

  loadEmployees() {
    this.loading = true;

    // TODO: Tutaj w przyszłości wstrzyknij serwis i pobierz dane z backendu.
    // np. this.employeeService.getAll().subscribe(data => this.employees = data);


  }
}
