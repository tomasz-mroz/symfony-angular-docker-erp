import { Component, OnInit, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TableModule } from 'primeng/table';
import { EmployeeService } from '../../../shared/services/employee.service';
import { CountedAndPaginated } from '../../../shared/interface/response/counted-and-paginated';
import { Observable } from 'rxjs';
import { Employee } from '../../../shared/interface/employee';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-all-employee-list',
  standalone: true,
  imports: [CommonModule, TableModule, RouterLink],
  templateUrl: './all-employee-list.component.html',
  styleUrl: './all-employee-list.component.scss',
})
export class AllEmployeeListComponent implements OnInit {
  private readonly employeeService = inject(EmployeeService);

  public employeeData$?: Observable<CountedAndPaginated<Employee>>;

  ngOnInit(): void {
    this.employeeData$ = this.employeeService.employeeList$;

    this.employeeService.loadList(0, 10, {});
  }
}
