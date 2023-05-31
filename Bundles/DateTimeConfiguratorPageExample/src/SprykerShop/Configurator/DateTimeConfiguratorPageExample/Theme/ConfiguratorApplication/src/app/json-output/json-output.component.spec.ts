import { ComponentFixture, TestBed } from '@angular/core/testing';

import { JsonOutputComponent } from './json-output.component';
import { AppModule } from '../app.module';

describe('JsonOutputComponent', () => {
    let component: JsonOutputComponent;
    let fixture: ComponentFixture<JsonOutputComponent>;

    beforeEach(async () => {
        await TestBed.configureTestingModule({
            imports: [AppModule],
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
