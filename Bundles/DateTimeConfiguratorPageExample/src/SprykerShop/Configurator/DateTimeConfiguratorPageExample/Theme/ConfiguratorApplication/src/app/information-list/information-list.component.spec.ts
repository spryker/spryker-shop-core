import { ComponentFixture, TestBed } from '@angular/core/testing';

import { InformationListComponent } from './information-list.component';

describe('InformationListComponent', () => {
    let component: InformationListComponent;
    let fixture: ComponentFixture<InformationListComponent>;

    beforeEach(async () => {
        await TestBed.configureTestingModule({
            declarations: [InformationListComponent],
        }).compileComponents();
    });

    beforeEach(() => {
        fixture = TestBed.createComponent(InformationListComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        expect(component).toBeTruthy();
    });
});
