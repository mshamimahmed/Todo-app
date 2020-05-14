import { Component, OnInit } from '@angular/core';
import { AuthenticationService } from '.././_services/authentication.service';
import { Router } from '@angular/router';
import { responseModel } from './responseModel'
import { from } from 'rxjs';

@Component({
  selector: 'app-profile',
  templateUrl: './profile.component.html',
  styleUrls: ['./profile.component.css']
})

export class ProfileComponent implements OnInit {
  
  warningMessage : string = "";
  UserName : string = "";
  email : string = "";
  fullname : string = "";
  bio : string = "";

  constructor(public authService: AuthenticationService, public router: Router) { 
  }

  ngOnInit(): void {
  }
  
 
  getTodoList(){

    this.authService.getTodoList()
        .subscribe(res => {
             //this.responseModel.username;
             // console.log(this.todolists);
          }, error => {
          this.warningMessage = "Someting went wrong "+error.warningMessage;
          console.error(error);
    });
  }

  updateProfile(){

    // if(!this.title || !this.description){
    //   this.warningMessage = 'Please Enter Title and Description';
    // }else{
      
    //   this.authService.addTodo(this.title, this.description)
    //     .subscribe(res => {
          
    //       this.title = "";
    //       this.description = "";
    //       this.warningMessage = 'Successfully Added';
    //       this.getTodoList();

    //       }, error => {
    //       this.warningMessage = "Someting went wrong "+error.warningMessage;
    //       console.error(error);
    //     });
    // }

   
  }

}
