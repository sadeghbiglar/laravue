<template>
    <div class="container">
        <div class="row justify-content-center mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Centers List</h3>

                        <div class="card-tools">
                            <div class="btn btn-success" @click="newModal"><i class="fa fa-user-plus fa-fw"></i>Add Center</div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Modify</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr  v-for="user in users" :key="user.id">
                                <td>{{ user.id }}</td>
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td>{{ user.type |propper }}</td>
                                <td>{{ user.created_at |formatDate }}</td>
                                <td>
                                    <i class="fa fa-edit blue" @click="editModal(user)"></i>
                                    /
                                <i class="fa fa-trash red" @click="deleteUser(user.id)"></i>

                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNewLabel"  v-show="!editMode">Add New User</h5>
                        <h5 class="modal-title" id="addNewLabel"  v-show="editMode">Update User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode? updateUser() : createUser()" >
                    <div class="modal-body">

                        <!-- //////////////// -->
                        <div class="form-group">
                            <input v-model="form.name" type="text" name="name" placeholder=" Enter Your Name"
                                   class="form-control" :class="{'is-invalid':form.errors.has('name')}">

                            <div v-if="form.errors.has('name')" v-html="form.errors.get('name')" />
                        </div>

                        <!-- //////////////// -->
                        <div class="form-group">
                            <input v-model="form.email" type="email" name="email" placeholder="Email Address"
                                   class="form-control" :class="{'is-invalid':form.errors.has('email')}">
                            <div v-if="form.errors.has('email')" v-html="form.errors.get('email')" />

                        </div>

                        <!-- //////////////// -->
                        <div class="form-group">
                            <textarea name="bio" id="bio" v-model="form.bio" placeholder="Enter User Bio"
                                   class="form-control" :class="{'is-invalid':form.errors.has('bio')}">
                           </textarea>
                            <div v-if="form.errors.has('bio')" v-html="form.errors.get('bio')" />
                        </div>
                        <!-- //////////////// -->
                        <div class="form-group">
                           <select name="type" id="type" v-model="form.type" class="form-control"
                                   :class="{'is-invalid':form.errors.has('type')}">
                               <option value="">Select User Type</option>
                               <option value="admin">Admin</option>
                               <option value="user">Standard User</option>
                                   </select>
                            <div v-if="form.errors.has('type')" v-html="form.errors.get('type')" />
                        </div>
                        <!-- //////////////// -->

                      <div class="form-group">
                          <input v-model="form.password" type="password" placeholder="password!"
                          class="form-control" :class="{'is-invalid':form.errors.has('password')}">
                          <div v-if="form.errors.has('password')" v-html="form.errors.get('password')" />
                      </div>
                        <!-- //////////////// -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" v-show="editMode" class="btn btn-success">Update</button>
                        <button type="submit" v-show="!editMode" class="btn btn-primary">Create</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <!-- // end container -->
</template>

<script>
    export default {
        data(){
           return{
               editMode:false,
               users:{},
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
        }, methods: {
            updateUser(){
                this.$Progress.start()
                this.form.put('api/user/'+this.form.id)
                .then(()=>{
                    Swal.fire(
                        'Updated!',
                        'Your user has been Updated.',
                        'success'
                    )
                    Fire.$emit('Reload');
                    $('#addNew').modal('hide');
                    this.$Progress.finish()
                })
                    .catch(()=>{
                    this.$Progress.fail()
                    Swal.fire(
                        'Not Updated!',
                        'Your user not Updated.',
                        'error'
                    )
                    Fire.$emit('Reload');
                })
            },
            editModal(user){
                this.form.reset();
                this.editMode=true;
                $('#addNew').modal('show');
                this.form.fill(user);
            },newModal(){
                this.editMode=false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            deleteUser(id){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.form.delete('api/user/'+id).then(()=>{
                            Swal.fire(
                                'Deleted!',
                                'Your user has been deleted.',
                                'success'
                            )
                            Fire.$emit('Reload');
                        }).catch(()=>{
                            Swal.fire(
                                'Not Deleted!',
                                'Your user not deleted.',
                                'error'
                            )
                        });

                    }
                })

            },
            loadUser(){

               axios.get('api/user').then(({data})=>(this.users=data.data));
            },
             createUser () {
                 this.$Progress.start()
                 this.form.post('api/user').then(()=>{
                     this.form.reset();
                     $('#addNew').modal('hide');
                     //this.loadUser();
                     Fire.$emit('Reload');
                     this.$Progress.finish();
                     Toast.fire({
                         icon: 'success',
                         title: 'User Created successfully'
                     })
                 });

            }
        },
        created(){
            this.loadUser();
            Fire.$on('Reload',()=>{
                this.loadUser();
            });
           // setInterval(()=>{this.loadUser()},300000);
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
