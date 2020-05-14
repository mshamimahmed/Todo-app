import { Component, OnInit } from '@angular/core';
import { AuthenticationService } from '.././_services/authentication.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {

  constructor(public authService: AuthenticationService, public router: Router) { 

  }

  public eamil: string = 'RohitSetty@gmail.com';
  public password: string = 'johnnyjacob';
  public warningMessage: string;
  ngOnInit(): void {
  }
   

  onLogIn() {

    if(!this.eamil || !this.password){
      this.warningMessage = 'Please Enter Login Details F#CK OFF';
    }else{
      
      this.authService.login(this.eamil, this.password)
        .subscribe(res => {
        
          this.warningMessage = '';
          sessionStorage.setItem('username', res['data']['username']);
          sessionStorage.setItem('email', res['data']['email']);
          sessionStorage.setItem('fullname', res['data']['fullname']);
          sessionStorage.setItem('LOGIN_STATUS', "SUCCESS");
          this.router.navigate(['/dashbaord']);

          }, error => {
          this.warningMessage = "Invalid Credentials!";
          console.error(error);
        });
    }
  }

  showDetails(){  
    this.router.navigate(['/dashbaord']);  
};  
}
