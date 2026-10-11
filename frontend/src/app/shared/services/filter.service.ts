import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root',
})
export class FilterService {
  constructor() {}

  public parseFilters(filters: object): string {
    let resultString = '';
    let filterValue: any = '';

    for (const [key, value] of Object.entries(filters)) {
      if (value === null || value === '' || (Array.isArray(value) && value.length === 0)) {
        continue;
      }

      if (resultString !== '') {
        resultString += '&';
      }

      if (value instanceof Date) {
        filterValue = this.formatDate(value);
      } else {
        filterValue = typeof value === 'string' ? value.trim() : value;
      }

      resultString += 'filter[' + encodeURIComponent(key) + ']=' + encodeURIComponent(filterValue);
    }

    return resultString;
  }

  public parseFiltersToObject(filters: object): object {
    const resultObject: { [key: string]: any } = {};
    let filterValue: any = '';

    for (const [key, value] of Object.entries(filters)) {
      if (value === null || value === '' || (Array.isArray(value) && value.length === 0)) {
        continue;
      }

      if (value instanceof Date) {
        filterValue = this.formatDate(value);
      } else {
        filterValue = typeof value === 'string' ? value.trim() : value;
      }

      resultObject[key] = filterValue;
    }

    return resultObject;
  }

  public parseFiltersFromQueryParams(types: { [key: string]: string }, filters?: any): any {
    const result: { [key: string]: any } = {};

    for (const [fieldName, fieldType] of Object.entries(types)) {
      if (!filters || !filters.hasOwnProperty(fieldName)) {
        result[fieldName] = fieldType.includes('[]') ? [] : '';
        continue;
      }

      const val = filters[fieldName];

      if (fieldType === 'string') {
        result[fieldName] = val;
        continue;
      }

      if (fieldType === 'int[]') {
        result[fieldName] = val ? val.split(',').map((v: string) => parseInt(v, 10)) : [];
        continue;
      }

      if (fieldType === 'int') {
        result[fieldName] = val ? parseInt(val, 10) : '';
        continue;
      }

      if (fieldType === 'string[]') {
        result[fieldName] = val ? val.split(',') : [];
        continue;
      }

      if (fieldType === 'date') {
        result[fieldName] = val ? new Date(val) : '';
        continue;
      }
    }

    return result;
  }

  public parseFiltersToQueryParams(filters: object): any {
    const resultObject: { [key: string]: string } = {};
    let filterValue: any = '';

    for (const [key, value] of Object.entries(filters)) {
      if (value === null || value === '' || (Array.isArray(value) && value.length === 0)) {
        continue;
      }

      if (value instanceof Date) {
        filterValue = this.formatDate(value);
      } else {
        filterValue = typeof value === 'string' ? value.trim() : value;
      }

      resultObject[key] = filterValue.toString();
    }

    return resultObject;
  }

  private formatDate(date: Date): string {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
  }
}
