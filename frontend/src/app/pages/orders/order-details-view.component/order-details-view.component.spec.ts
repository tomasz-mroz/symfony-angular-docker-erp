import { ComponentFixture, TestBed } from '@angular/core/testing';

import { OrderDetailsViewComponent } from './order-details-view.component';

describe('OrderDetailsViewComponent', () => {
  let component: OrderDetailsViewComponent;
  let fixture: ComponentFixture<OrderDetailsViewComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [OrderDetailsViewComponent],
    }).compileComponents();

    fixture = TestBed.createComponent(OrderDetailsViewComponent);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
