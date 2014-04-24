<?php

use route\DefaultRoute;
use route\Route;

Route::register("e404", new DefaultRoute("error/404", array(
                                                           "controller" => "e404",
                                                           "action" => "display"
                                                      ))
);

Route::register("d1", new DefaultRoute("", array(
                                                "controller" => "index",
                                                "action"     => "display"
                                           ))
);

Route::register("d2", new DefaultRoute("<controller>", array(
                                                            "action" => "display"
                                                       ), array(
                                                               "controller" => "[^/]+"
                                                          )) 
);

//Route::register("d4", new DefaultRoute("<controller>/<action>/<id>", array(), array(
//                                                                              "controller" => "[^/]+",
//                                                                              "action" => "[^/]+"
//                                                                         ))
//);

Route::register("d3", new DefaultRoute("<controller>/<action>", array(), array(
                                                                              "controller" => "[^/]+",
                                                                              "action" => "[^/]+"
                                                                         ))
);

