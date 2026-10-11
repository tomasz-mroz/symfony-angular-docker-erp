import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AllEmployeeListComponent } from './all-employee-list.component';

describe('AllEmployeeListComponent', () => {
  let component: AllEmployeeListComponent;
  let fixture: ComponentFixture<AllEmployeeListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AllEmployeeListComponent],
    }).compileComponents();

    fixture = TestBed.createComponent(AllEmployeeListComponent);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
