import { Component, inject, signal } from '@angular/core';
import { Router, RouterOutlet, RouterLink, RouterLinkActive } from '@angular/router';
import { CommonModule } from '@angular/common';

import { ButtonModule } from 'primeng/button';
import { AvatarModule } from 'primeng/avatar';
import { MenuModule } from 'primeng/menu';
import { TooltipModule } from 'primeng/tooltip';
import { AuthService } from '../../../shared/services/auth.service';

interface NavItem {
  label: string;
  icon: string;
  routerLink: string;
  badge?: string;
}

@Component({
  selector: 'app-layout',
  standalone: true,
  imports: [
    CommonModule,
    RouterOutlet,
    RouterLink,
    RouterLinkActive,
    ButtonModule,
    AvatarModule,
    MenuModule,
    TooltipModule,
  ],
  templateUrl: './layout.component.html',
  styleUrl: './layout.component.scss',
})
export class LayoutComponent {
  private authService = inject(AuthService);

  sidebarCollapsed = signal(false);

  // Przykładowe zakładki (placeholdery pod przyszłe moduły)
  navItems = [
    {
      label: 'Dashboard',
      icon: 'pi pi-home',
      routerLink: '/dashboard',
    },
    {
      label: 'Pracownicy',
      icon: 'pi pi-users',
      routerLink: '/pracownicy', // Jeśli masz widok listy, lub zostaw puste/przekieruj
      children: [{ label: 'Dodaj', icon: 'pi pi-user-plus', routerLink: '/pracownicy/dodaj' }],
    },
  ];

  toggleSidebar(): void {
    this.sidebarCollapsed.update((val) => !val);
  }

  onLogout(): void {
    this.authService.logout();
  }
}
