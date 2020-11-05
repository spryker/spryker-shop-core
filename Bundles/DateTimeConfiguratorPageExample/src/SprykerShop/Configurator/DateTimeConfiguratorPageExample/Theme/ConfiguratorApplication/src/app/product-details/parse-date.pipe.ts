import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
    name: 'parseDate'
})
export class ParseDatePipe implements PipeTransform {
    transform(date: string): string {
        return date.split('.').reverse().join('-');
    }
}
