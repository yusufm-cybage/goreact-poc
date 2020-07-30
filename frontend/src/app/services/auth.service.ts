import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
@Injectable({
  providedIn: 'root'
})
export class AuthService {
  baseURL: string = environment.baseURL;
  header: any;
  constructor(private http: HttpClient) { 
    this.header = new HttpHeaders({
      'Content-Type': 'application/json'
    });
  }

  login(credentials): Observable<any> {
    return this.http.post(this.baseURL + '', credentials, {
      headers: this.header
    });
  }
}
