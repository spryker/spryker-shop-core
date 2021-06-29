import { ComponentFixture, TestBed } from '@angular/core/testing';

import { JsonOutputComponent } from './json-output.component';

describe('JsonOutputComponent', () => {
    let component: JsonOutputComponent;
    let fixture: ComponentFixture<JsonOutputComponent>;

    beforeEach(async () => {
        await TestBed.configureTestingModule({
            declarations: [JsonOutputComponent],
        }).compileComponents();
    });

    beforeEach(() => {
        fixture = TestBed.createComponent(JsonOutputComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        expect(component).toBeTruthy();
    });
});
