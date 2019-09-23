![enter image description here](https://github.com/tolgayildizz/laravel-5.7-email-verification-and-auth-via-api/blob/master/pics/banner.jpeg?raw=true)

**Laravel 5.7 REST API Email Verification and Authentication**
-
Packages used

 - tymon/jwt-auth: 1.0.0-rc.2
 - axios : 0.18
 - vue: 2.5.17 (Optional)
 - vue-router: 3.0.2 (Optional)  
 - vuex: 3.1.0 (Optional)
 
What does it do;
 - Email Verification via API
 - Authentication via API
 - Add, delete and update users via API


How can I use?
-
 1. Clone the repository first
 
	```git clone https://github.com/tolgayildizz/laravel-5.7-email-verification-and-auth-via-api.git```
	
 2. You must load dependencies after cloning
	 ```composer update```
	 ```npm install```
	 
 3. Get an .env file from the .env.example file
	 (the following codes only work in linux)
	 ```cp .env.example .env```
	 ```gedit .env```
	 
 4. Create the database and enter the information in the .env file
 5. Get a laravel key
	 ```php artisan key:generate```
	 
 6. Get a jwt key
	 ```php artisan jwt:secret```
	 
 7. Create tables
	 ```php artisan migrate:fresh```
	 
 8. Start Laravel server
	```php artisan serve --port=8000```

![It's alive](https://github.com/tolgayildizz/laravel-5.7-email-verification-and-auth-via-api/blob/master/pics/tenor.gif?raw=true)

**The application works, but how do I use it?**
-
We need an email server for email confirmation. Here we will help us mailtrap.io.

 1. Sign up for free at mailtrap.io
 2. Place the settings in the demo inbox in your .env file
 3. Then run the following command;
	```php artisan config:cache```
	
 4. Now our mail server is ready. But we still need an API request tool. My preference is Insomnia but you can use postman. In the meantime, I would recommend Insomnia to you. https://insomnia.rest/ An open source REST client.
 
 **Let's start our requests now**
 -
 
Let's start with the registration process
```http://127.0.0.1:8000/api/auth/register```

Required fields;

 1. email
 2. name
 3. password
 4. password_confirmation
 
 Headers;
 
 1. Accept: application/json
 2. Content-Type: application/json
 
![Register Request](https://github.com/tolgayildizz/laravel-5.7-email-verification-and-auth-via-api/blob/master/pics/Register-request-1.jpg?raw=true)

![Register Request 2](https://github.com/tolgayildizz/laravel-5.7-email-verification-and-auth-via-api/blob/master/pics/Register-request-2.jpg?raw=true)

Now let's do the login request

```http://127.0.0.1:8000/api/auth/login```

Required fields;

 1. email
 2. password
 
  Headers;
 
 1. Accept: application/json
 2. Content-Type: application/json
 
 ![Login Request](https://github.com/tolgayildizz/laravel-5.7-email-verification-and-auth-via-api/blob/master/pics/Login-Request.jpg?raw=true)

The transaction was successful and returned us a token. We will proceed through this token. So let's copy this token value.

Now we will make a request to reach our information. We will use the token value here. (Actually we'll always use it after that).

Here you can send the token value with form or headers. I'm gonna use the headers.

```http://127.0.0.1:8000/api/auth/me```

Optional fields;

 1. token
 
  Headers;
 
 1. Accept: application/json
 2. Content-Type: application/json
 3. Authorization: Bearer + "your token"
 
 ![Me Request](https://github.com/tolgayildizz/laravel-5.7-email-verification-and-auth-via-api/blob/master/pics/Me-Request.jpg?raw=true) 

Now let's have a request to renew the password.

```http://127.0.0.1:8000/api/profile/current/set-password```

Required fields;

 1. newPassword
 2. email
 3. token
 4. currentPassword
 
  Headers;
 
 1. Accept: application/json
 2. Content-Type: application/json
 
![Set Password](https://github.com/tolgayildizz/laravel-5.7-email-verification-and-auth-via-api/blob/master/pics/set-Password.jpg?raw=true)

Password change operation completed successfully. 
Now let's check if the email is verified. (A mail is sent automatically when a user is registered.)

```http://127.0.0.1:8000/api/email/verify/```

Optional fields;

 1. token
 
  Headers;
 
 1. Accept: application/json
 2. Content-Type: application/json
 3. Authorization : Bearer + "your token"
 
 (Do not forget to login again because we renew the password!)

![Email Verify Control](https://github.com/tolgayildizz/laravel-5.7-email-verification-and-auth-via-api/blob/master/pics/Email-Verify-Control.jpg?raw=true)

(Email returned as a message unverified. Because we haven't done the email verification yet.)

We've come to the part where things are getting a little complicated. Now we need to get a verification mail. (In fact, this mail came automatically when I signed up, but I'm putting this part to show the resend process).

```http://127.0.0.1:8000/api/email/resend/```

Optional fields;

 1. token
 
  Headers;
 
 1. Accept: application/json
 2. Content-Type: application/json
 3. Authorization : Bearer + "your token"
 
 ![Email Resend](https://github.com/tolgayildizz/laravel-5.7-email-verification-and-auth-via-api/blob/master/pics/Email-Resend.jpg?raw=true)

![Email Verify Mail](https://github.com/tolgayildizz/laravel-5.7-email-verification-and-auth-via-api/blob/master/pics/Email-verify-post.png?raw=true)

As you can see, the mail came. Now, what we need to do is get the signature that this link returns to us.

Example email verify link: 
```http://127.0.0.1:8000/email/verify?queryURL=http%3A%2F%2F127.0.0.1%3A8000%2Fapi%2Femail%2Fverify%2F10%3Fexpires%3D1548685326%26signature%3Da9e04bf0f188a490832439f868cf9d5b4c60dc33dfa155a80145075c328fcd0c```

Example queryURL :
```http://127.0.0.1:8000/api/email/verify/10?expires=1548678462&signature=baa0af50040f689eccd241157d6b14708b1ec37e2fe94277c9aa45aee9cf8d69```

I've defined a vue-router and component to capture this queryURL.
Operation logic is very simple. ```/email/verify/``` retrieves the queryURL of the request to the route.

Example vue-component: 
```
<template>  
 <div> 
	 <div  class="mt-2 alert alert-warning" role="alert">
		 Email verification is taking place.  
	 </div>  
 </div>
</template>  
  
<script>  
  export default {  
        async mounted() { 
            const { queryURL } = this.$route.query;  
			console.log('post this url with your token', queryURL, "Sample = Authorization : Bearer + your token" );  
			  await axios.post(queryURL, {  
                'Access-Control-Allow-Origin':'*',  
				'Content-Type':'X-CSRF-TOKEN',  
				}).then(x => {  
	                console.log(x);  
				}).catch(x => {  
	                console.log(x);  
				});  
		  }  
    }  
</script>
```

I made the post process with the axios when it was connected to the component project. You can use different methods and frontend frameworks. It's just a component to explain its logic.

Let's do the same with Insomnia now.

Optional fields;

 1. token
 
  Headers;
 
 1. Accept: application/json
 2. Content-Type: application/json
 3. Authorization : Bearer + "your token"
 
 ![Email Verified Success](https://github.com/tolgayildizz/laravel-5.7-email-verification-and-auth-via-api/blob/master/pics/Email-verified-success.jpg?raw=true)

Email seems to be verified. Let's check it out.

```http://127.0.0.1:8000/api/email/verify/```

Let's have a request here and send our token together.

![Email verified](https://github.com/tolgayildizz/laravel-5.7-email-verification-and-auth-via-api/blob/master/pics/Email-verfiy-check.jpg?raw=true)

As you can see, we received an email confirmation.

So how do we query the payload of our token?

```http://127.0.0.1:8000/api/auth/payload/```

We have to make a request to. This returns the payload of our token. Of course we have to send our current token.

Optional fields;

 1. token
 
  Headers;
 
 1. Accept: application/json
 2. Content-Type: application/json
 3. Authorization : Bearer + "your token"

![Token payload](https://github.com/tolgayildizz/laravel-5.7-email-verification-and-auth-via-api/blob/master/pics/token-payload.jpg?raw=true)

As you can see, this is how we reach the payload information about our token.

Now, let's logout.

```http://127.0.0.1:8000/api/auth/logout/```

Optional fields;

 1. token
 
  Headers;
 
 1. Accept: application/json
 2. Content-Type: application/json
 3. Authorization : Bearer + "your token"

![Logout](https://github.com/tolgayildizz/laravel-5.7-email-verification-and-auth-via-api/blob/master/pics/logout.jpg?raw=true) 

We have completed our exit process successfully.

We need to renew our tokens within a certain time period. This is an important detail for security. (I prefer to do it every 45 minutes and immediately after important requests.)

We need to request a refresh for this.

```http://127.0.0.1:8000/api/auth/refresh/```

Optional fields;

 1. token
 
  Headers;
 
 1. Accept: application/json
 2. Content-Type: application/json
 3. Authorization : Bearer + "your token"

![Token Refresh](https://github.com/tolgayildizz/laravel-5.7-email-verification-and-auth-via-api/blob/master/pics/refresh-token.jpg?raw=true)

(In this process, make sure you have a token.)

So what happens when we forget our password?

First, we need to send a forgot password email by sending email and resetUrl to our server. (The ResetUrl frontend is also related to the address you use. For example, if you want to be redirected to the /forgot/password/reset/ page after you forget the password, send it as url.)

My resetUrl address is ```127.0.0.1:8000//profile/forgot-password-reset/```

The address of the request is as follows;

```127.0.0.1:8000/api/auth/forgot-password-email/```

Required fields;

 1. email
 2. resetUrl
 
  Headers;
 
 1. Accept: application/json
 2. Content-Type: application/json

IMPORTANT! At the end of resetUrl, we will have to add  to /token/ email/.

Example resetUrl;

```127.0.0.1:8000//profile/forgot-password-reset/<token>/<email>```

The token and email must be in <>. Please note that the important note is token and email. Otherwise there will be an error.

Let's request now.

![Forgot Password Request](https://github.com/tolgayildizz/laravel-5.7-email-verification-and-auth-via-api/blob/master/pics/forgot-password-email.png?raw=true)

The transaction was successful and the email reset mail came in our inbox.

![Forgot Password Inbox](https://github.com/tolgayildizz/laravel-5.7-email-verification-and-auth-via-api/blob/master/pics/forgot-password-inbox.jpg?raw=true)

Now click on the link provided on the button. Let's go with the signature.
(<> the token and email we sent in was filled in and sent back to us and we will have to catch it)

```
<template>  
</template>  
  
<script>  
  import router from 'vue-router';  
  export default {  
        name: "ForgotPasswordEmail",  
		data() {  
            return {  
                token:'',  
				email:'',  
				password:'testpasswords', //Input incoming data (model="password")  
			 }  
		  },  
		 async created() {  
	            this.token = this.$route.params.token;  
				this.email = this.$route.params.email;  
				await axios.post('http://127.0.0.1:8000/api/auth/forgot-password-reset/',  
				  { 
                    token: this.token,  
					email: this.email,  
					password: this.password,  
				  },  
				  {  
	                'Access-Control-Allow-Origin':'*',  
					'Content-Type':'multipart/form-data',  
					'Accept':'application/json',  
				 }).then(response => {  
		              console.log(response.data.message)  
		         })  
	              .catch(error => {  
	                    console.error(error.response.data.message)  
                });  
		  }  
	    }  
</script>
```

I have reset the password by capturing the token and email values ​​when the page is created and posting the new parallax with the axios to the address below. You can use different methods.

Insomnia via post to this address we can perform the operation. (OPTIONAL)

```http://127.0.0.1:8000/api/auth/forgot-password-reset/```

![Forgot Password Reset](https://github.com/tolgayildizz/laravel-5.7-email-verification-and-auth-via-api/blob/master/pics/forgot-password-reset.png?raw=true)

That's all we can do for now. But in the future there will be more features. Obviously, I have tailored this warehouse to my own needs. It can gain more features than returns.

An important note: I tried to explain the Vue framework as simple as possible. I felt like I didn't have to know the vue because every laravel uses it. People are using React and Jquery too. That's why I had to keep it pretty simple.

[For more attention on vue.](https://vuejs.org/)

Things To-do
-
In fact this will be shaped according to the number of people who use it (ie, the stars) and my needs. I will try to keep the package up-to-date if there is a simpler usage in new laravel versions.

I plan to add in the future;

 1. Socialite
 2. This project is a clone written with laravel / passport
 3. Multi Permission (Authorization)
 4. Vue and React Page
 5. Clone of this project written with adonis.js.


Is there a problem?
-
Don't hesitate to write me if you get a mistake.

Instagram: tolga._.yildiz
E-mail : yildiztolgaa@gmail.com
