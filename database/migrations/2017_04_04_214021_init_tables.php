<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InitTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('image_category_translations');
        Schema::dropIfExists('image_translations');
        Schema::dropIfExists('product_category_translations');
        Schema::dropIfExists('product_item_translations');
        Schema::dropIfExists('product_option_translations');
        Schema::dropIfExists('product_category_images');
        Schema::dropIfExists('product_item_images');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('product_options');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('product_items');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('image_categories');
        Schema::dropIfExists('images');
        Schema::dropIfExists('users');

        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->engine = 'InnoDB';

                $table->increments('id');
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->string('role')->nullable()->default(null);
                $table->tinyInteger('verified')->default(0);
                $table->string('verification_token')->nullable();
                $table->rememberToken();
                $table->softDeletes();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('password_resets')) {
            Schema::create('password_resets', function (Blueprint $table) {
                $table->engine = 'InnoDB';

                $table->string('email')->index();
                $table->string('token')->index();
                $table->timestamp('created_at')->nullable();
            });
        }

        //

        if (!Schema::hasTable('image_categories')) {
            Schema::create('image_categories', function (Blueprint $table) {
                $table->engine = 'InnoDB';

                $table->increments('id');
                $table->string('name');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('images')) {
            Schema::create('images', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                //$table->dropForeign('user_id_img_fk');
                //$table->dropForeign('image_category_id_img');

                $table->increments('id');
                $table->integer('user_id')->unsigned()->nullable()->default(null);
                $table->integer('image_category_id')->unsigned()->nullable()->default(null);
                $table->string('name');
                $table->string('title');
                $table->string('comment');
                $table->boolean('public');
                $table->string('location');
                $table->string('location_thumb')->nullable();
                $table->timestamps();

                if(config('database.enable_constraints')) {
                    $table->foreign('user_id', 'user_id_img_fk')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
                    $table->foreign('image_category_id', 'image_category_id_img')->references('id')->on('image_categories')->onDelete('set null')->onUpdate('cascade');
                }
            });
        }

        if (!Schema::hasTable('product_categories')) {
            Schema::create('product_categories', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                //$table->dropForeign('prod_cat_img_fk');

                $table->increments('id');
                $table->string('name');
                $table->string('description')->nullable()->default(null);
                $table->integer('image_id')->index()->unsigned()->nullable()->default(null);
                $table->integer('parent_category_id')->index()->unsigned()->nullable()->default(null);
                $table->tinyInteger('visible')->default(1);
                $table->timestamps();

                if(config('database.enable_constraints')) {
                    $table->foreign('image_id', 'prod_cat_img_fk')->references('id')->on('images')->onDelete('set null')->onUpdate('cascade');
                    $table->foreign('parent_category_id', 'prod_cat_prod_cat_fk')->references('id')->on('product_categories')->onDelete('set null')->onUpdate('cascade');
                }
            });
        }

        if (!Schema::hasTable('brands')) {
            Schema::create('brands', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                //$table->dropForeign('brand_img_fk');

                $table->increments('id');
                $table->string('name');
                $table->string('description')->nullable()->default(null);
                $table->integer('image_id')->index()->unsigned()->nullable()->default(null);
                $table->timestamps();

                if(config('database.enable_constraints')) {
                    $table->foreign('image_id', 'brand_img_fk')->references('id')->on('images')->onDelete('set null')->onUpdate('cascade');
                }
            });
        }

        if (!Schema::hasTable('product_items')) {
            Schema::create('product_items', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                //$table->dropForeign('product_item_category_fk');
                //$table->dropForeign('product_brand_fk');

                $table->increments('id');
                $table->integer('product_category_id')->index()->unsigned()->nullable();
                $table->string('name');
                $table->integer('brand_id')->index()->unsigned()->nullable();
                $table->string('description');
                $table->float('price');
                $table->float('promotion_price')->nullable()->default(null);
                $table->float('tax');
                $table->float('deliveryprice')->nullable()->default(null);
                $table->string('deliverytime')->nullable()->default(null);
                $table->tinyInteger('active')->default(1);
                //$table->integer('stock')->nullable()->default(0);
                $table->timestamps();

                if(config('database.enable_constraints')) {
                    $table->foreign('product_category_id', 'product_item_category_fk')->references('id')->on('product_categories')->onDelete('set null')->onUpdate('cascade');
                    $table->foreign('brand_id', 'product_brand_fk')->references('id')->on('brands')->onDelete('set null')->onUpdate('cascade');
                }
            });
        }

        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                //$table->dropForeign('order_user_fk');

                $table->increments('id');
                $table->integer('user_id')->unsigned()->nullable();
                $table->float('total_paid');
                $table->boolean('archived')->default(false);
                $table->softDeletes();
                $table->timestamps();

                if(config('database.enable_constraints')) {
                    $table->index('user_id');
                    $table->foreign('user_id', 'order_user_fk')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
                }
            });
        }

        if (!Schema::hasTable('product_options')) {
            Schema::create('product_options', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                //$table->dropForeign('product_option_item_fk');

                $table->increments('id');
                $table->integer('product_item_id')->index()->unsigned()->nullable();
                $table->string('name');
                $table->string('description')->nullable()->default(null);
                $table->float('price')->nullable()->default(null);
                $table->float('tax')->nullable()->default(null);
                $table->integer('stock')->unsigned()->nullable()->default(0);
                $table->timestamps();

                if(config('database.enable_constraints')) {
                    $table->foreign('product_item_id', 'product_option_item_fk')->references('id')->on('product_items')->onDelete('cascade')->onUpdate('cascade');
                }
            });
        }

        if (!Schema::hasTable('carts')) {
            Schema::create('carts', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                //$table->dropForeign('cart_user_fk');

                $table->increments('id');
                $table->integer('user_id')->index()->unsigned()->nullable();
                $table->timestamps();

                if(config('database.enable_constraints')) {
                    $table->foreign('user_id', 'cart_user_fk')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
                }
            });
        }

        if (!Schema::hasTable('cart_items')) {
            Schema::create('cart_items', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                //$table->dropForeign('cart_item_cart_fk');
                //$table->dropForeign('cart_item_product_fk');
                //$table->dropForeign('cart_item_product_option');

                $table->increments('id');
                $table->integer('cart_id')->index()->unsigned()->nullable();
                $table->integer('product_item_id')->index()->unsigned()->nullable();
                $table->integer('quantity')->default(1);
                $table->integer('product_option_id')->index()->unsigned()->nullable();
                $table->timestamps();

                if(config('database.enable_constraints')) {
                    $table->foreign('cart_id', 'cart_item_cart_fk')->references('id')->on('carts')->onDelete('cascade')->onUpdate('cascade');
                    $table->foreign('product_item_id', 'cart_item_product_fk')->references('id')->on('product_items')->onDelete('cascade')->onUpdate('cascade');
                    $table->foreign('product_option_id', 'cart_item_product_option')->references('id')->on('product_options')->onDelete('set null')->onUpdate('cascade');
                }
            });
        }

        if (!Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                //$table->dropForeign('order_item_order_fk');
                //$table->dropForeign('order_item_product_item_fk');
                //$table->dropForeign('order_item_product_option_fk');

                $table->increments('id');
                $table->integer('order_id')->index()->unsigned()->nullable();
                $table->integer('product_item_id')->index()->unsigned()->nullable();
                $table->integer('product_option_id')->index()->unsigned()->nullable();
                $table->integer('quantity');
                $table->timestamps();

                if(config('database.enable_constraints')) {
                    $table->foreign('order_id', 'order_item_order_fk')->references('id')->on('orders')->onDelete('cascade')->onUpdate('cascade');
                    $table->foreign('product_item_id', 'order_item_product_item_fk')->references('id')->on('product_items')->onDelete('set null')->onUpdate('cascade');
                    $table->foreign('product_option_id', 'order_item_product_option_fk')->references('id')->on('product_options')->onDelete('set null')->onUpdate('cascade');
                }
            });
        }

        if (!Schema::hasTable('product_item_images')) {
            Schema::create('product_item_images', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                //$table->dropForeign('product_item_img_prod_item_fk');
                //$table->dropForeign('product_item_option_img_prod_item_fk');
                //$table->dropForeign('product_item_img_product_item_fk');

                $table->increments('id');
                $table->integer('product_item_id')->index()->unsigned();
                $table->integer('product_option_id')->index()->unsigned()->nullable();
                $table->integer('image_id')->index()->unsigned();
                $table->timestamps();

                if(config('database.enable_constraints')) {
                    $table->foreign('product_item_id', 'product_item_img_prod_item_fk')->references('id')->on('product_items')->onDelete('cascade')->onUpdate('cascade');
                    $table->foreign('product_option_id', 'product_item_option_img_prod_item_fk')->references('id')->on('product_options')->onDelete('cascade')->onUpdate('cascade');
                    $table->foreign('image_id', 'product_item_img_product_item_fk')->references('id')->on('images')->onDelete('cascade')->onUpdate('cascade');
                }
            });
        }

        if (!Schema::hasTable('product_category_images')) {
            Schema::create('product_category_images', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                //$table->dropForeign('product_ct_img_pr_it_fk');
                //$table->dropForeign('pro_cat_img_img_fk');

                $table->increments('id');
                $table->integer('product_category_id')->unsigned()->nullable();
                $table->integer('image_id')->index()->unsigned()->nullable();
                $table->timestamps();

                if(config('database.enable_constraints')) {
                    $table->foreign('product_category_id', 'product_ct_img_pr_it_fk')->references('id')->on('product_categories')->onDelete('cascade')->onUpdate('cascade');
                    $table->foreign('image_id', 'pro_cat_img_img_fk')->references('id')->on('images')->onDelete('cascade')->onUpdate('cascade');
                }
            });
        }

        /*
        if (!Schema::hasTable('brand_images')) {
            Schema::create('brand_images', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                //$table->dropForeign('brand_pr_it_fk');
                //$table->dropForeign('brand_img_it_fk');

                $table->increments('id');
                $table->integer('brand_id')->unsigned()->nullable();
                $table->integer('image_id')->index()->unsigned()->nullable();
                $table->timestamps();

                if(config('database.enable_constraints')) {
                    $table->foreign('brand_id', 'brand_pr_it_fk')->references('id')->on('brands')->onDelete('cascade')->onUpdate('cascade');
                    $table->foreign('image_id', 'brand_img_it_fk')->references('id')->on('images')->onDelete('cascade')->onUpdate('cascade');
                }
            });
        }
        */

        //translations

        if (!Schema::hasTable('product_category_translations')) {
            Schema::create('product_category_translations', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                //$table->dropForeign('product_ct_transl_pr_it_fk');

                $table->increments('id');
                $table->integer('product_category_id')->unsigned()->nullable();
                $table->string('language');//->default('en');
                $table->string('name')->nullable()->default(null);
                $table->string('description')->nullable()->default(null);
                $table->timestamps();

                if(config('database.enable_constraints')) {
                    $table->foreign('product_category_id', 'product_ct_transl_pr_it_fk')->references('id')->on('product_categories')->onDelete('cascade')->onUpdate('cascade');
                }
            });
        }

        if (!Schema::hasTable('image_translations')) {
            Schema::create('image_translations', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                //$table->dropForeign('img_transl_fk');

                $table->increments('id');
                $table->integer('image_id')->index()->unsigned()->nullable();
                $table->string('language');//->default('en');
                $table->string('title')->nullable()->default(null);
                $table->string('comment')->nullable()->default(null);
                $table->timestamps();

                if(config('database.enable_constraints')) {
                    $table->foreign('image_id', 'img_transl_fk')->references('id')->on('images')->onDelete('cascade')->onUpdate('cascade');
                }
            });
        }

        if (!Schema::hasTable('image_category_translations')) {
            Schema::create('image_category_translations', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                //$table->dropForeign('img_cat_transl_fk');

                $table->increments('id');
                $table->integer('image_category_id')->index()->unsigned()->nullable();
                $table->string('language');//->default('en');
                $table->string('name')->nullable()->default(null);
                $table->timestamps();

                if(config('database.enable_constraints')) {
                    $table->foreign('image_category_id', 'img_cat_transl_fk')->references('id')->on('image_categories')->onDelete('cascade')->onUpdate('cascade');
                }
            });
        }

        if (!Schema::hasTable('product_item_translations')) {
            Schema::create('product_item_translations', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                //$table->dropForeign('prod_item_transl_fk');

                $table->increments('id');
                $table->integer('product_item_id')->index()->unsigned()->nullable();
                $table->string('language');//->default('en');
                $table->string('name')->nullable()->default(null);
                $table->string('description')->nullable()->default(null);
                $table->timestamps();

                if(config('database.enable_constraints')) {
                    $table->foreign('product_item_id', 'prod_item_transl_fk')->references('id')->on('product_items')->onDelete('cascade')->onUpdate('cascade');
                }
            });
        }

        if (!Schema::hasTable('product_option_translations')) {
            Schema::create('product_option_translations', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                //$table->dropForeign('prod_opt_transl_fk');

                $table->increments('id');
                $table->integer('product_option_id')->index()->unsigned()->nullable();
                $table->string('language');//->default('en');
                $table->string('name')->nullable()->default(null);
                $table->string('description')->nullable()->default(null);
                $table->timestamps();

                if(config('database.enable_constraints')) {
                    $table->foreign('product_option_id', 'prod_opt_transl_fk')->references('id')->on('product_options')->onDelete('cascade')->onUpdate('cascade');
                }
            });
        }

        if (!Schema::hasTable('brand_translations')) {
            Schema::create('brand_translations', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                //$table->dropForeign('brand_transl_fk');

                $table->increments('id');
                $table->integer('brand_id')->index()->unsigned()->nullable();
                $table->string('language');//->default('en');
                $table->string('name')->nullable()->default(null);
                $table->string('description')->nullable()->default(null);
                $table->timestamps();

                if(config('database.enable_constraints')) {
                    $table->foreign('brand_id', 'brand_transl_fk')->references('id')->on('brands')->onDelete('cascade')->onUpdate('cascade');
                }
            });
        }

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('image_category_translations');
        Schema::dropIfExists('image_translations');
        Schema::dropIfExists('product_category_translations');
        Schema::dropIfExists('product_item_translations');
        Schema::dropIfExists('product_option_translations');
        Schema::dropIfExists('product_category_images');
        Schema::dropIfExists('product_item_images');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('product_options');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('product_items');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('images');
        Schema::dropIfExists('image_categories');
        Schema::dropIfExists('users');
    }
}
