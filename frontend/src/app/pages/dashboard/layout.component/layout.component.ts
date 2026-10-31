import { Component, inject, signal, OnInit } from '@angular/core';
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

interface AppModule {
  key: string;
  label: string;
  icon: string;
  navItems: NavItem[];
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
  isDarkMode = signal(false);

  ngOnInit(): void {
    const savedTheme = localStorage.getItem('erp_theme');
    if (savedTheme === 'dark') {
      this.isDarkMode.set(true);
      document.body.classList.add('dark');
    }
  }

  toggleDarkMode(): void {
    this.isDarkMode.update((val) => {
      const nextVal = !val;
      if (nextVal) {
        document.body.classList.add('dark');
        localStorage.setItem('erp_theme', 'dark');
      } else {
        document.body.classList.remove('dark');
        localStorage.setItem('erp_theme', 'light');
      }
      return nextVal;
    });
  }

  modules: AppModule[] = [
    {
      key: 'kadry',
      label: 'Kadry',
      icon: 'pi pi-users',
      navItems: [
        {
          label: 'Dashboard',
          icon: 'pi pi-home',
          routerLink: '/dashboard',
        },
        {
          label: 'Pracownicy',
          icon: 'pi pi-id-card',
          expanded: true,
          children: [
            { label: 'Lista', icon: 'pi pi-list', routerLink: '/pracownicy' },
            { label: 'Dodaj', icon: 'pi pi-user-plus', routerLink: '/pracownicy/dodaj' },
          ],
        },
        {
          label: 'Nieobecności',
          icon: 'pi pi-calendar-times',
          routerLink: '/kadry/nieobecnosci',
        },
        {
          label: 'Płace i umowy',
          icon: 'pi pi-file-excel',
          routerLink: '/kadry/place',
        },
      ],
    },
    {
      key: 'produkcja',
      label: 'Produkcja',
      icon: 'pi pi-cog',
      navItems: [
        {
          label: 'Dashboard',
          icon: 'pi pi-home',
          routerLink: '/produkcja/dashboard',
        },
        {
          label: 'Zlecenia',
          icon: 'pi pi-briefcase',
          children: [
            { label: 'Aktywne', icon: 'pi pi-play', routerLink: '/produkcja/zlecenia/aktywne' },
            {
              label: 'Zakończone',
              icon: 'pi pi-check',
              routerLink: '/produkcja/zlecenia/zakonczone',
            },
          ],
        },
        { label: 'Maszyny', icon: 'pi pi-wrench', routerLink: '/produkcja/maszyny' },
        { label: 'Normy czasowe', icon: 'pi pi-clock', routerLink: '/produkcja/normy' },
      ],
    },
    {
      key: 'magazyn',
      label: 'Magazyn',
      icon: 'pi pi-box',
      navItems: [
        {
          label: 'Dashboard',
          icon: 'pi pi-home',
          routerLink: '/magazyn/dashboard',
        },
        {
          label: 'Stany magazynowe',
          icon: 'pi pi-database',
          children: [
            { label: 'Surowce', icon: 'pi pi-circle', routerLink: '/magazyn/surowce' },
            { label: 'Wyroby gotowe', icon: 'pi pi-star', routerLink: '/magazyn/produkty' },
          ],
        },
        { label: 'Przyjęcia (PZ)', icon: 'pi pi-arrow-down-left', routerLink: '/magazyn/pz' },
        { label: 'Wydania (WZ)', icon: 'pi pi-arrow-up-right', routerLink: '/magazyn/wz' },
      ],
    },
    {
      key: 'system',
      label: 'System',
      icon: 'pi pi-desktop',
      navItems: [
        {
          label: 'Ustawienia ogólne',
          icon: 'pi pi-sliders-h',
          routerLink: '/system/ustawienia',
        },
        {
          label: 'Użytkownicy i role',
          icon: 'pi pi-users',
          children: [
            { label: 'Konta użytkowników', icon: 'pi pi-user', routerLink: '/system/uzytkownicy' },
            { label: 'Uprawnienia', icon: 'pi pi-lock', routerLink: '/system/uprawnienia' },
          ],
        },
        { label: 'Logi systemowe', icon: 'pi pi-list', routerLink: '/system/logi' },
        { label: 'Kopie zapasowe', icon: 'pi pi-cloud-upload', routerLink: '/system/backup' },
      ],
    },
  ];

  activeModuleKey: string = 'kadry';
  currentSidebarItems: NavItem[] = this.modules[0].navItems;

  selectModule(key: string): void {
    this.activeModuleKey = key;
    const found = this.modules.find((m) => m.key === key);
    if (found) {
      this.currentSidebarItems = found.navItems;
    }
  }

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
