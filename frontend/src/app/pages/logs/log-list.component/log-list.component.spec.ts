import { ComponentFixture, TestBed } from '@angular/core/testing';

import { LogListComponent } from './log-list.component';

describe('LogListComponent', () => {
  let component: LogListComponent;
  let fixture: ComponentFixture<LogListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [LogListComponent],
    }).compileComponents();

    fixture = TestBed.createComponent(LogListComponent);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
