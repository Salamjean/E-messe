@extends('admin.layouts.template')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center"
                        style="background-color: #5ea7b5; color: white;">
                        <h3 class="card-title">Messages de Contact</h3>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="contactMessagesTable">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Sujet</th>
                                        <th>Message</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($messages as $message)
                                        <tr>
                                            <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                                            <td>{{ $message->name }}</td>
                                            <td>
                                                <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                                            </td>
                                            <td>{{ $message->subject }}</td>
                                            <td>
                                                <span title="{{ $message->message }}">
                                                    {{ Str::limit($message->message, 50) }}
                                                </span>
                                            </td>
                                            <td>
                                                <form action="{{ route('content.contact-messages.destroy', $message) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            $('#contactMessagesTable').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json"
                }
            });
        });
    </script>
@endpush
