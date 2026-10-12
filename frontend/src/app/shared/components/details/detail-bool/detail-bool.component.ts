import { Component, computed, input } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TagModule } from 'primeng/tag';

@Component({
  selector: 'app-detail-bool',
  standalone: true,
  imports: [CommonModule, TagModule],
  templateUrl: './detail-bool.component.html',
  styleUrls: ['./detail-bool.component.scss'],
})
export class DetailBoolComponent {
  readonly header = input<string>('');
  readonly value = input<boolean | null | undefined>(null);

  readonly trueLabel = input<string>('Tak');
  readonly falseLabel = input<string>('Nie');

  readonly trueSeverity = input<'success' | 'info' | 'warn' | 'danger' | 'secondary' | 'contrast'>(
    'success',
  );
  readonly falseSeverity = input<'success' | 'info' | 'warn' | 'danger' | 'secondary' | 'contrast'>(
    'danger',
  );

  readonly isEmpty = computed<boolean>(() => {
    const v = this.value();
    return v === null || v === undefined;
  });

  readonly label = computed<string>(() => {
    const v = this.value();
    if (this.isEmpty()) return '—';
    return v ? this.trueLabel() : this.falseLabel();
  });

  readonly severity = computed(() => {
    const v = this.value();
    return v ? this.trueSeverity() : this.falseSeverity();
  });

  readonly icon = computed<string>(() => {
    const v = this.value();
    if (this.isEmpty()) return 'pi pi-minus';
    return v ? 'pi pi-check' : 'pi pi-times';
  });
}
