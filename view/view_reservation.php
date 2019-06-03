<!DOCTYPE html>
<html>
    <head>        
        <meta charset="UTF-8">

        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
        <title>filter</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous">
        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" crossorigin="anonymous">
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
        <script src="lib/jquery-3.3.1.min.js" type="text/javascript"></script>
        <script src="lib/jquery-validation-1.19.0/jquery.validate.min.js" type="text/javascript"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" crossorigin="anonymous"></script>

        <script src="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.js" type="text/javascript"></script>
        <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
        <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.theme.min.css" rel="stylesheet" type="text/css"/>
        <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.structure.min.css" rel="stylesheet" type="text/css"/>
                                
    </head>
    <body id="body">
        <script>
            var list;
            var actual;
            $(function () {
                $('#btnsearch').hide();
                $('#search').focus();
                list = $('.list').val();
                actual = $('#memberz').val();
                role = $('#userrole').val();
                $('#search').keyup(function () {
                    console.log($('#memberz').val());
                    $.get("book/find_book/" + $('#search').val() + "/" + $('#memberz').val(), function (data) {
                        var DN = JSON.parse(data);
                        displayTable(DN);
                        console.log(actual);
                    });
                });
            });
            function displayTable(datas) {
                var html = "<tr>\n\
                        <th id='isbn' >ISBN</th>" +
                        "<th id='title'>Title</th>" +
                        "<th id='author' >Athor</th>" +
                        "<th id='editor' >Editor</th>" +
                        "<th id='action' >Action</th>\</tr>";
                for (var m = 0; m < datas.length; ++m) {
                    html += "<tr>";
                    html += "<td>" + datas[m].isbn + "</td>";
                    html += "<td>" + datas[m].title + "</td>";
                    html += "<td>" + datas[m].author + "</td>";
                    html += "<td>" + datas[m].editor + "</td>";
                    if (role === 'admin') {
                        html += "<td> <form action='book/edit' method='post'><input name='edit' value='" + datas[m].id + "' hidden><input class='submit' type='submit' value='" + "edit" + "'></form> </td>";
                        html += "<td> <form action='book/delete' method='post'><input name='id_book' value='" + datas[m].id + "' hidden><input  type='submit' value='" + "delete" + "'></form></td>";
                    }
                    if (role != 'admin') {
                        html += "<td> <form action='book/details' method='post'><input name='details' value='" + datas[m].id + "' hidden><input  type='submit' value='" + "details" + "'></form></td>";
                    }
                    html += "<td> <form action='rental/selection' method='post'><input name='selection' value='" + datas[m].id + "' hidden><input name='selections' value='" + actual + "' hidden><input class='submit' type='submit' value='" + "selection" + "'></form> </td>";
                    html += "</tr>";
                }
                $('#list').html(html);
            }

            function functionjs() {
                console.log($('#idToDelete').val());

                $.get("rental/deleteAllJS/" + $('#idToDelete').val(), function (data) {
                    console.log(data);
                    location.reload()
                });
            }

            function createjs() {
                console.log($('#idToSave').val());

                $.post("rental/createJS/", {createid: $('#idToSave').val()}, function (data) {
                    console.log(data);
                    location.reload()
                });
            }


            function popupdelete(id, author, title) {
                $("#id").text(id);
                $("#author").text(author);
                $("#title").text(title);

                $('#confirmDialog').dialog({

                    buttons: {
                        retour: function () {
                            $(this).dialog("close");
                        },
                        delete: function () {
                            $.post("book/deletebookJS", {delete: $('#ibook').val()}, function (data) {
                                location.relaod();
                            });
                            $(this).dialog("close");
                        }
                    }
                });
            }

        </script>
        <div class="title">Welcome <?= $user->username ?></div>
        <?php
        if ($user->isAdmin($user->username)) {
            include('menuAdmin.html');
        } else {
            include('menu.html');
        }
        ?>
        <div class="list">
            <form action="book/search" method="post">
                <table >
                    <thead>
                        <tr>
                            <td>Filters</td>
                            <td><input  name="critere" type="text" id="search"  placeholder="text"></td>
                    <input type='hidden' id ="userconnect" name='member' value='<?= $smember->username ?>' >
                    <input type='hidden' id ="userrole" name='userrole' value='<?= $user->role ?>' >
                    <td ><input type="submit" name="search" id="btnsearch"></td>

                    </tr>
                    </thead>
                </table>
            </form><br>
            <table id="list">
                <thead>
                    <tr>
                        <th>ISBN</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Editor</th>
                       <!--<th>image</th>-->
                        <th>action</th>
                    </tr>
                </thead>
                <?php foreach ($books as $book) : ?>
                    <tr>
                        <td><?= $book->isbn ?></td>
                        <td><?= $book->title ?></td>
                        <td><?= $book->author ?></td>
                        <td><?= $book->editor ?></td>

                 <!--<td><img src="upload/<?= $book->picture ?>" style="width:30%; " /></td>-->

                        <td>
                            <?php if ($user->isAdmin($user->username)): ?>
                                <form  action='book/edit' method='post'>
                                    <input type='hidden' name='edit' value='<?= $book->id ?>'>
                                    <input type='submit' value='edit'>
                                </form>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if ($user->isAdmin($user->username)): ?>
                                <form  action='book/delete' method='post'>
                                    <input type='hidden' name='id_book' value='<?= $book->id ?>'>
                                    <input type='submit' value='delete'>
                                </form>

<!--                                <input id="ibook" type='hidden' name='id_book' value='<?= $book->id ?>'>
                                <input type='submit' value='deletepopup' onclick="popupdelete('<?= $book->id ?>', '<?= $book->title ?>', '<?= $book->author ?>')">
-->

                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if (($user->isManager($user->username)) || ($user->isMember($user->username))): ?>
                                <form  action='book/details' method='post'>
                                    <input type='hidden' name='details' value='<?= $book->id ?>'>
                                    <input type='submit' value='details'>
                                </form>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form   action='rental/selection' method='post'>
                                <input type='hidden' name='selection' value='<?= $book->id ?>' >
                                <input type='hidden' name='selections' value='<?= $smember->id ?>' >
                                <input type='submit' value='selection'>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>





            <?php if ($user->isAdmin($user->username)): ?>
                <form class="button" action="book/add_book" method="POST">
                    <input type="submit" value="Add Book"  name="new">
                </form>
            <?php endif; ?>
            <div class="title">Basket of  <?= $smember->username ?></div>
            Basket of books to rent
            <br>
            <table >
                <thead>
                    <tr>
                        <th>ISBN</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Editor</th>
                        <th>action</th>
                    </tr>
                </thead>
                <tbody>
                    <!--            selection est un ensemble de pré-réservation
                                    en appuyant sur selection on ajoute au panier de l'utilisateur courant/séléctionné
                                    une pré-réservation pour un livre dont la date de début est null-->

                    <?php foreach ($selections as $selection): ?>

                        <tr>
                            <td><?= $selection->isbn ?></td>
                            <td><?= $selection->title ?></td>
                            <td><?= $selection->author ?></td>
                            <td><?= $selection->editor ?></td>
                            <td>
                            <td>
                                <form   action='rental/deselection' method='post'>
                                    <input type='hidden' name='deselection' value='<?= $selection->title ?>' >
                                    <input type='hidden' name='sdeselection' value='<?= $smember->username ?>' >
                                    <input type='submit' value='deselection'>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (($user->isManager($user->username)) || ($user->isAdmin($user->username))): ?>
                <form class="button" action="rental/user_choice" method="POST">
                    <td>The basket is for:</td>
                    <td>                      
                        <select id="member" name="rental_select" value="rental_select" > 
                            <option value= '<?= $smember->id ?: '' ?>'id="memberz"><?= $smember->username ?></option>
                            <?php foreach ($members as $member): ?>
                                <option value=  '<?= $member->username ?: '' ?>'  ><?= $member->username ?></option>
                            <?php endforeach; ?>   
                    </td>
                    </select>
                    <input type="submit" value="Valider user"   name="new">
                </form>    
            <?php endif; ?><?php ?>


            <form class="button" action="rental/clear_basket" method="POST">
                <input type='hidden' name='memberclearbasket' value='<?= $smember->username ?>' >
                <input type="submit" value="Effacer rental"   name="new">
            </form>




            <input id="idToDelete" type='hidden' name='memberclearbasket' value='<?= $smember->id ?>' >
            <input type="submit" value="Effacer JS"   name="new" onclick="functionjs()">


            <input id="idToSave" type='hidden' name='memberconfirmbasket' value='<?= $smember->id ?>' >
            <input type="submit" name="create sv" value="create JS" onclick="createjs()" >




            <form class="button" action="rental/confirm_basket2" method="POST">
                <input type='hidden' name='memberconfirmbasket' value='<?= $smember->username ?>' >
                <input type="submit" name="Save rental" value="Save rental" >
            </form> 
        </div>

        <div id="confirmDialog" hidden>
            <p hidden>id: <strong id="id"></strong></p>
            <p>author: <strong id="athor"></strong></p>
            <p>Titre: <strong id="title"></strong></p>
            <p>Date de location: <strong id="start"></strong></p>
            <p>Date de Retour:<strong id="end"></strong></p>
        </div>

    </body>
</html>