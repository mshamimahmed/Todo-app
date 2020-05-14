import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { NoteDeatailsComponent } from './note-deatails.component';

describe('NoteDeatailsComponent', () => {
  let component: NoteDeatailsComponent;
  let fixture: ComponentFixture<NoteDeatailsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ NoteDeatailsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(NoteDeatailsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
