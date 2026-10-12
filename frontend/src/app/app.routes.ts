import { Routes } from '@angular/router';
import { LoginComponent } from './pages/auth/login.component/login.component';
import { DashboardComponent } from './pages/dashboard/dashboard.component/dashboard.component';
import { authGuard } from './shared/guards/auth.guard';
import { LayoutComponent } from './pages/dashboard/layout.component/layout.component';
import { EmployeeAddComponent } from './pages/employees/employee-add.component/employee-add.component';
import { AllEmployeeListComponent } from './pages/employees/all-employee-list.component/all-employee-list.component';
import { EmployeeDetailsComponent } from './pages/employees/employee-details.component/employee-details.component';

export const routes: Routes = [
  { path: 'login', component: LoginComponent },

  {
    path: '',
    component: LayoutComponent,
    canActivate: [authGuard],
    children: [
      { path: 'dashboard', component: DashboardComponent },

      { path: 'pracownicy', component: AllEmployeeListComponent },
      { path: 'pracownicy/dodaj', component: EmployeeAddComponent },
      { path: 'pracownicy/szczegoly/:id', component: EmployeeDetailsComponent },

      { path: '', redirectTo: 'dashboard', pathMatch: 'full' },
    ],
  },

  { path: '**', redirectTo: 'login' },
];
