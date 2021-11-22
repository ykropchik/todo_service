# Todo Service

<details>
  
<details>
  <summary>Запрос на регистрацию</summary>

  ![Авторизация](https://github.com/ykropchik/todo_service/blob/main/imgs/registration.png)
    
</details>

<details>
  <summary>Запрос на авторизацию</summary>

  ![Авторизация](https://github.com/ykropchik/todo_service/blob/main/imgs/auth.png)
    
</details>
  <summary>Запросы</summary>

<details>
  <summary>Запрос списка todo</summary>

  ![Авторизация](https://github.com/ykropchik/todo_service/blob/main/imgs/getTodoList.png)
  
</details>

<details>
  <summary>Запрос на добавление todo</summary>

  ![Авторизация](https://github.com/ykropchik/todo_service/blob/main/imgs/createTodoItem.png)
    
</details>

<details>
   <summary>Запрос на изменение todo</summary>

  ![Авторизация](https://github.com/ykropchik/todo_service/blob/main/imgs/itemUpdate.png)
    
</details>

<details>
   <summary>Запрос на удаление todo</summary>

  ![Авторизация](https://github.com/ykropchik/todo_service/blob/main/imgs/itemRemove.png)
    
</details>

<details>
  <summary>Запрос на добавление файла</summary>

  ![Авторизация](https://github.com/ykropchik/todo_service/blob/main/imgs/uploadFile.png)
    
</details>

<details>
   <summary>Запрос на получение файла</summary>

  ![Авторизация](https://github.com/ykropchik/todo_service/blob/main/imgs/getFile.png)
    
</details>

<details>
   <summary>Запрос на удаление файла</summary>

  ![Авторизация](https://github.com/ykropchik/todo_service/blob/main/imgs/removeFile.png)
    
</details>

<details>
   <summary>Запрос на получения списка файлов</summary>

  ![Авторизация](https://github.com/ykropchik/todo_service/blob/main/imgs/getFilesList.png)
    
</details>
  
</details>

<details>
  <summary>Code sniffers task</summary>
  
  <details>
   <summary>До исправлений</summary>

  ![PHPSTAN-before](https://github.com/ykropchik/todo_service/blob/main/imgs/phpstan-before.png)
  ![PHPCS-before-part1](https://github.com/ykropchik/todo_service/blob/main/imgs/phpcs-before-part1.png)
  ![PHPCS-before-part2](https://github.com/ykropchik/todo_service/blob/main/imgs/phpcs-before-part2.png)
  ![PHPCS-before-part3](https://github.com/ykropchik/todo_service/blob/main/imgs/phpcs-before-part3.png)
  ![PHPCS-before-part4](https://github.com/ykropchik/todo_service/blob/main/imgs/phpcs-before-part4.png)

  <details>
    <summary>PHP-ECS</summary>

  ```bash
  root@ubuntu-s-1vcpu-1gb-fra1-01:/var/www/todo_service/server# vendor/bin/ecs check src
  19/19 [▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓] 100%


  1) src/Controller/FileController.php

      ---------- begin diff ----------
  @@ -84,7 +84,7 @@
              ], Response::HTTP_FORBIDDEN);
          }

  -        $responsedFile = $this->getParameter('files_directory').'/'.$file->getSafeName();
  +        $responsedFile = $this->getParameter('files_directory') . '/' . $file->getSafeName();

          return new BinaryFileResponse($responsedFile);
      }
  @@ -117,7 +117,7 @@

          $filesystem = new Filesystem();
          try {
  -            $filesystem->remove([$this->getParameter('files_directory').'/'.$file->getSafeName()]);
  +            $filesystem->remove([$this->getParameter('files_directory') . '/' . $file->getSafeName()]);
          } catch (IOExceptionInterface $exception) {
              return $this->response([
                  'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
      ----------- end diff -----------


  Applied checkers:

  * PhpCsFixer\Fixer\Operator\ConcatSpaceFixer



  2) src/Kernel.php

      ---------- begin diff ----------
  @@ -14,11 +14,11 @@
      protected function configureContainer(ContainerConfigurator $container): void
      {
          $container->import('../config/{packages}/*.yaml');
  -        $container->import('../config/{packages}/'.$this->environment.'/*.yaml');
  +        $container->import('../config/{packages}/' . $this->environment . '/*.yaml');

  -        if (is_file(\dirname(__DIR__).'/config/services.yaml')) {
  +        if (is_file(\dirname(__DIR__) . '/config/services.yaml')) {
              $container->import('../config/services.yaml');
  -            $container->import('../config/{services}_'.$this->environment.'.yaml');
  +            $container->import('../config/{services}_' . $this->environment . '.yaml');
          } else {
              $container->import('../config/{services}.php');
          }
  @@ -26,10 +26,10 @@

      protected function configureRoutes(RoutingConfigurator $routes): void
      {
  -        $routes->import('../config/{routes}/'.$this->environment.'/*.yaml');
  +        $routes->import('../config/{routes}/' . $this->environment . '/*.yaml');
          $routes->import('../config/{routes}/*.yaml');

  -        if (is_file(\dirname(__DIR__).'/config/routes.yaml')) {
  +        if (is_file(\dirname(__DIR__) . '/config/routes.yaml')) {
              $routes->import('../config/routes.yaml');
          } else {
              $routes->import('../config/{routes}.php');
      ----------- end diff -----------


  Applied checkers:

  * PhpCsFixer\Fixer\Operator\ConcatSpaceFixer



  3) src/Security/UserAuthenticator.php

      ---------- begin diff ----------
  @@ -52,7 +52,7 @@

          // For example:
          //return new RedirectResponse($this->urlGenerator->generate('some_route'));
  -        throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
  +        throw new \Exception('TODO: provide a valid redirect inside ' . __FILE__);
      }

      protected function getLoginUrl(Request $request): string
      ----------- end diff -----------


  Applied checkers:

  * PhpCsFixer\Fixer\Operator\ConcatSpaceFixer



  4) src/Service/FileUploader.php

      ---------- begin diff ----------
  @@ -21,7 +21,7 @@
      {
          $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
          $safeFilename = $this->slugger->slug($originalFilename);
  -        $fileName = 'todoService-'.uniqid().'.'.$file->guessExtension();
  +        $fileName = 'todoService-' . uniqid() . '.' . $file->guessExtension();

          try {
              $file->move($this->targetDirectory, $fileName);
      ----------- end diff -----------


  Applied checkers:

  * PhpCsFixer\Fixer\Operator\ConcatSpaceFixer



                                                                                                                          
  [WARNING] 4 errors are fixable! Just add "--fix" to console command and rerun to apply. 
  ```
    
  </details>

  <details>
    <summary>PHPMD</summary>

  ```bash
  root@ubuntu-s-1vcpu-1gb-fra1-01:/var/www/todo_service/server# vendor/bin/phpmd src text cleancode
  /var/www/todo_service/server/src/Controller/FileController.php:170      Missing class import via use statement (line '170', column '38').
  /var/www/todo_service/server/src/Controller/SecurityController.php:34   Missing class import via use statement (line '34', column '19').
  /var/www/todo_service/server/src/Controller/TodoItemController.php:87   Missing class import via use statement (line '87', column '38').
  /var/www/todo_service/server/src/DataFixtures/TodoItemsFixtures.php:17  Missing class import via use statement (line '17', column '25').
  /var/www/todo_service/server/src/DataFixtures/TodoItemsFixtures.php:22  Missing class import via use statement (line '22', column '37').
  /var/www/todo_service/server/src/Encoder/NixillaJWTEncoder.php:25       Avoid using static access to class '\JWT\Authentication\JWT' in method 'encode'.
  /var/www/todo_service/server/src/Encoder/NixillaJWTEncoder.php:37       Avoid using static access to class '\JWT\Authentication\JWT' in method 'decode'.
  /var/www/todo_service/server/src/Kernel.php:22  The method configureContainer uses an else expression. Else clauses are basically not necessary and you can simplify the code by not using them.
  /var/www/todo_service/server/src/Kernel.php:34  The method configureRoutes uses an else expression. Else clauses are basically not necessary and you can simplify the code by not using them.
  /var/www/todo_service/server/src/Security/UserAuthenticator.php:47      Avoid assigning values to variables in if clauses and the like (line '49', column '13').
  /var/www/todo_service/server/src/Security/UserAuthenticator.php:55      Missing class import via use statement (line '55', column '19').
  ```

  </details>
  
  </details>
  
  <details>
   <summary>Исправления</summary>

  ![PHPCBF-use](https://github.com/ykropchik/todo_service/blob/main/imgs/phpcbf-use.png)
  ![PHP-CS](https://github.com/ykropchik/todo_service/blob/main/imgs/php-cs-fixer.png)
    
  </details>
  
  <details>
    <summary>После исправлений</summary>

  ![PHPCS-after](https://github.com/ykropchik/todo_service/blob/main/imgs/phpcs-after-allfixes.png)
  ![PHP-ECS-after](https://github.com/ykropchik/todo_service/blob/main/imgs/php-ecs-after.png)
  ![PHPSTAN-after](https://github.com/ykropchik/todo_service/blob/main/imgs/phpstan-after.png)

  <details>
    <summary>PHPMD</summary>

  ```bash
  root@ubuntu-s-1vcpu-1gb-fra1-01:/var/www/todo_service/server# vendor/bin/phpmd src text unusedcode
  /var/www/todo_service/server/src/Security/UserAuthenticator.php:47      Avoid unused parameters such as '$token'.
  /var/www/todo_service/server/src/Security/UserAuthenticator.php:58      Avoid unused parameters such as '$request'.
  /var/www/todo_service/server/src/Service/FileUploader.php:22    Avoid unused local variables such as '$originalFilename'.
  ```

  </details>
    
  </details>
  
  
</details>
