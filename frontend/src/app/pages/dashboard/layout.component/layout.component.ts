import { Component, inject, signal } from '@angular/core';
import { Router, RouterOutlet, RouterLink, RouterLinkActive } from '@angular/router';
import { CommonModule } from '@angular/common';

import { ButtonModule } from 'primeng/button';
import { AvatarModule } from 'primeng/avatar';
import { MenuModule } from 'primeng/menu';
import { TooltipModule } from 'primeng/tooltip';
import { MenuItem } from 'primeng/api';
import { AuthService } from '../../../shared/services/auth.service';

interface NavItem {
  label: string;
  icon: string;
  routerLink?: string;
  badge?: string;
  children?: NavItem[];
  expanded?: boolean;
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

  navItems: NavItem[] = [
    {
      label: 'Dashboard',
      icon: 'pi pi-home',
      routerLink: '/dashboard',
    },
    {
      label: 'Pracownicy',
      icon: 'pi pi-users',
      expanded: false,
      children: [
        { label: 'Lista', icon: 'pi pi-list', routerLink: '/pracownicy/lista' },
        { label: 'Dodaj', icon: 'pi pi-user-plus', routerLink: '/pracownicy/dodaj' },
      ],
    },
  ];

  userMenuItems: MenuItem[] = [
    {
      label: 'Mój profil',
      icon: 'pi pi-user',
      command: () => {
        console.log('Mój profil');
      },
    },
    {
      label: 'Powiadomienia',
      icon: 'pi pi-bell',
      command: () => {
        console.log('Powiadomienia');
      },
    },
    {
      separator: true,
    },
    {
      label: 'Wyloguj',
      icon: 'pi pi-power-off',
      styleClass: 'text-red-500',
      command: () => {
        this.onLogout();
      },
    },
  ];

  toggleSidebar(): void {
    this.sidebarCollapsed.update((val) => !val);
  }

  toggleSubmenu(item: NavItem): void {
    item.expanded = !item.expanded;
  }

  onLogout(): void {
    this.authService.logout();
  }
}
