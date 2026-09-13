import { Routes } from '@angular/router';
import { LoginComponent } from './pages/auth/login.component/login.component';
import { DashboardComponent } from './pages/dashboard/dashboard.component/dashboard.component';
import { authGuard } from './shared/guards/auth.guard';
import { LayoutComponent } from './pages/dashboard/layout.component/layout.component';
import { EmployeeAddComponent } from './pages/employees/employee-add.component/employee-add.component';
import { EmployeeListComponent } from './pages/employees/employee-list.component/employee-list.component';

export const routes: Routes = [
  { path: 'login', component: LoginComponent },

  {
    path: '',
    component: LayoutComponent,
    canActivate: [authGuard],
    children: [
      { path: 'dashboard', component: DashboardComponent },

      { path: 'pracownicy', component: EmployeeListComponent },
      { path: 'pracownicy/dodaj', component: EmployeeAddComponent },

      { path: '', redirectTo: 'dashboard', pathMatch: 'full' },
    ],
  },

  { path: '**', redirectTo: 'login' },
];
