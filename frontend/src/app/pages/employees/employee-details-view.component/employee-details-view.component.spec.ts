import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EmployeeDetailsViewComponent } from './employee-details-view.component';

describe('EmployeeDetailsViewComponent', () => {
  let component: EmployeeDetailsViewComponent;
  let fixture: ComponentFixture<EmployeeDetailsViewComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [EmployeeDetailsViewComponent],
    }).compileComponents();

    fixture = TestBed.createComponent(EmployeeDetailsViewComponent);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
