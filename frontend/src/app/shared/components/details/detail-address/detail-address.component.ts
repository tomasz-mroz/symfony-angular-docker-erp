import { Component, computed, input } from '@angular/core';
import { CommonModule } from '@angular/common';

import { CardModule } from 'primeng/card';

import { DetailComponent } from '../detail/detail.component';
import { Address } from '../../../interface/address';

@Component({
  selector: 'app-detail-address',
  standalone: true,
  imports: [CommonModule, CardModule, DetailComponent],
  templateUrl: './detail-address.component.html',
  styleUrls: ['./detail-address.component.scss'],
})
export class DetailAddressComponent {
  readonly address = input.required<Address>();
  readonly header = input<string>('');

  readonly hasState = computed<boolean>(() => !!this.address()?.state);

  readonly hasDistrictOrCommune = computed<boolean>(() => {
    const a = this.address();
    return !!a?.district || !!a?.commune;
  });

  readonly hasPostCity = computed<boolean>(() => !!this.address()?.postCity);
}
