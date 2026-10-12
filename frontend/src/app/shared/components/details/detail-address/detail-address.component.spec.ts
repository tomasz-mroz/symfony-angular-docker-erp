import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DetailAddressComponent } from './detail-address.component';

describe('DetailAddressComponent', () => {
  let component: DetailAddressComponent;
  let fixture: ComponentFixture<DetailAddressComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [DetailAddressComponent],
    }).compileComponents();

    fixture = TestBed.createComponent(DetailAddressComponent);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
