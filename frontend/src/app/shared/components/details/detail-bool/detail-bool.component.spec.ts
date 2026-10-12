import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DetailBoolComponent } from './detail-bool.component';

describe('DetailBoolComponent', () => {
  let component: DetailBoolComponent;
  let fixture: ComponentFixture<DetailBoolComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [DetailBoolComponent],
    }).compileComponents();

    fixture = TestBed.createComponent(DetailBoolComponent);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
