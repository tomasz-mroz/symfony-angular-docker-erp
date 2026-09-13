import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { TableModule } from 'primeng/table';
import { InputTextModule } from 'primeng/inputtext';
import { ButtonModule } from 'primeng/button';

// Interfejs opisujący strukturę danych pracownika
export interface Employee {
  id: string;
  firstName: string;
  lastName: string;
  position: string;
}

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

    // Poniżej symulujemy opóźnienie sieci i wczytujemy przykładowe dane:
    setTimeout(() => {
      this.employees = [
        { id: '1', firstName: 'Jan', lastName: 'Kowalski', position: 'Senior Angular Developer' },
        { id: '2', firstName: 'Anna', lastName: 'Nowak', position: 'Scrum Master' },
        { id: '3', firstName: 'Piotr', lastName: 'Wiśniewski', position: 'DevOps Engineer' },
        { id: '4', firstName: 'Katarzyna', lastName: 'Wójcik', position: 'UX/UI Designer' },
        { id: '5', firstName: 'Michał', lastName: 'Kowalczyk', position: 'Backend Developer' },
        { id: '6', firstName: 'Magdalena', lastName: 'Lewandowska', position: 'HR Manager' },
        { id: '7', firstName: 'Kamil', lastName: 'Zieliński', position: 'QA Tester' },
      ];
      this.loading = false;
    }, 800);
  }
}
