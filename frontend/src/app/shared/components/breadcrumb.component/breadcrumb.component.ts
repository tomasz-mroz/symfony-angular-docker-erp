import { AfterViewInit, Component, computed, inject, input } from '@angular/core';
import { Title } from '@angular/platform-browser';
import { BreadcrumbModule } from 'primeng/breadcrumb';
import { MenuItem } from 'primeng/api';

@Component({
  selector: 'app-breadcrumb',
  standalone: true,
  imports: [BreadcrumbModule],
  templateUrl: './breadcrumb.component.html',
  styleUrls: ['./breadcrumb.component.scss'],
})
export class BreadcrumbComponent implements AfterViewInit {
  private readonly titleService = inject(Title);

  // Signal-based inputs (Angular 17.1+)
  readonly master = input<string>('');
  readonly parentName = input<string>('');
  readonly name = input<string>('');

  readonly items = computed<MenuItem[]>(() => {
    const result: MenuItem[] = [];

    if (this.master()) {
      result.push({ label: this.master() });
    }
    if (this.parentName()) {
      result.push({ label: this.parentName() });
    }
    if (this.name()) {
      result.push({ label: this.name() });
    }

    return result;
  });

  ngAfterViewInit(): void {
    this.titleService.setTitle(`${this.parentName()} > ${this.name()} :: PM System`);
  }
}
