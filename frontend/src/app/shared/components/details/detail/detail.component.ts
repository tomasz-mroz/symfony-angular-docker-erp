import { Component, computed, input, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { DomSanitizer, SafeUrl } from '@angular/platform-browser';

@Component({
  selector: 'app-detail',
  standalone: true,
  imports: [CommonModule, RouterModule],
  templateUrl: './detail.component.html',
  styleUrls: ['./detail.component.scss'],
})
export class DetailComponent {
  private readonly sanitizer = inject(DomSanitizer);

  readonly header = input<string>('');

  readonly value = input<string | number | boolean | null | undefined>(null);

  readonly link = input<string[] | undefined>(undefined);

  readonly externalLink = input<string | undefined>(undefined);

  readonly showEmpty = input<boolean>(true);

  readonly displayValue = computed<string>(() => {
    const v = this.value();
    if (v === null || v === undefined || v === '') {
      return this.showEmpty() ? '—' : '';
    }
    if (typeof v === 'boolean') {
      return v ? 'Tak' : 'Nie';
    }
    return String(v);
  });

  readonly isEmpty = computed<boolean>(() => {
    const v = this.value();
    return v === null || v === undefined || v === '';
  });

  readonly safeExternalLink = computed<SafeUrl | null>(() => {
    const url = this.externalLink();
    return url ? this.sanitizer.bypassSecurityTrustUrl(url) : null;
  });
}
