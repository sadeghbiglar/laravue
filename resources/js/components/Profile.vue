<template>
    <div class="container">
        <div class="row justify-content-center">


                                <div class="col-md-3">

                                    <!-- Profile Image -->
                                    <div class="card card-primary card-outline">
                                        <div class="card-body box-profile">
                                            <div class="text-center">
                                                <img class="profile-user-img img-fluid img-circle"
                                                    :src="getProfilePhoto()"
                                                     alt="User profile picture">
                                                <p><label for="photo">change</label></p>
                                            </div>

                                            <h3 class="profile-username text-center">{{ form.name }}</h3>

                                            <p class="text-muted text-center">{{form.type}}</p>

                                            <ul class="list-group list-group-unbordered mb-3">
                                                <li class="list-group-item">
                                                    <b>Followers</b> <a class="float-right">1,322</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Following</b> <a class="float-right">543</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>Friends</b> <a class="float-right">13,287</a>
                                                </li>
                                            </ul>

                                            <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                    <!-- /.card -->

                                    <!-- About Me Box -->

                                    <!-- /.card -->
                                </div>
                                <!-- /.col -->
                                <div class="col-md-9">
                                    <div class="card">
                                        <div class="card-header p-2">
                                            <ul class="nav nav-pills">
                                                <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Settings</a></li>
                                                <li class="nav-item"><a class="nav-link " href="#activity" data-toggle="tab">Activity</a></li>
                                                <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Timeline</a></li>

                                            </ul>
                                        </div><!-- /.card-header -->
                                        <div class="card-body">
                                            <div class="tab-content">
                                                <div class=" tab-pane" id="activity">
                                                    <!-- Post -->

                                                    <!-- /.post -->
                                                </div>
                                                <!-- /.tab-pane -->
                                                <div class="tab-pane" id="timeline">

                                                </div>
                                                <!-- /.tab-pane -->

                                                <div class="active tab-pane" id="settings">
                                                    <form class="form-horizontal">
                                                        <div class="form-group row">
                                                            <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control" :class="{'is-invalid':form.errors.has('name')}" id="inputName" v-model="form.name" placeholder="Name">
                                                                <div v-if="form.errors.has('name')" v-html="form.errors.get('name')" />
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                                            <div class="col-sm-10">
                                                                <input type="email" class="form-control" :class="{'is-invalid':form.errors.has('email')}" id="inputEmail" v-model="form.email" placeholder="Email">
                                                                <div v-if="form.errors.has('email')" v-html="form.errors.get('email')" />
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="inputName2" class="col-sm-2 col-form-label">Bio</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control" id="inputName2" v-model="form.bio" placeholder="bio">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="inputExperience" class="col-sm-2 col-form-label">Password</label>
                                                            <div class="col-sm-10">
                                                                <textarea class="form-control" :class="{'is-invalid':form.errors.has('password')}" id="inputExperience" v-model="form.password" placeholder="password"></textarea>
                                                                <div v-if="form.errors.has('password')" v-html="form.errors.get('password')" />
                                                            </div>
                                                        </div> <div class="form-group row" hidden>
                                                            <div class="col-sm-10">
                                                                <input type="file" @change="updateProfile" class="form-control" id="photo"  ></input>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <div class="offset-sm-2 col-sm-10">
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="offset-sm-2 col-sm-10">
                                                                <button @click.prevent="updateInfo" type="submit" class="btn btn-danger">Submit</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <!-- /.tab-pane -->
                                            </div>
                                            <!-- /.tab-content -->
                                        </div><!-- /.card-body -->
                                    </div>
                                    <!-- /.card -->
                                </div>
                                <!-- /.col -->

        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                // create a new form
                form:new Form({
                    id:'',
                    name:'',
                    email:'',
                    password:'',
                    type:'',
                    photo:'',
                    bio:''

                })
            }
        },
        methods:{
            getProfilePhoto(){
              return "img/profile/"+this.form.photo;
            },
            updateInfo(){
                this.$Progress.start();
              this.form.put('api/profile')
                  .then(()=>{


                      this.$Progress.finish()
                      location.reload(true);
                  })
                  .catch(()=>{
                      this.$Progress.fail()
                  });
            },
            updateProfile(e){
                let file = e.target.files[0];
                let reader = new FileReader();
                if(file['size']<2111775)
                {
                    reader.onloadend = (file)=> {
                    this.form.photo=reader.result

                      }
                    reader.readAsDataURL(file);

                }else {
                    Swal.fire(
                        'Error-Your File Is Large !',
                        'Oops.',
                        'error'
                    )
                }


            }
        },
        created() {
            axios.get('api/profile').then(({data})=>(this.form.fill(data)));
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
