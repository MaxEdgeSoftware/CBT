@extends('layouts.admin')

@section('content')
    <section class="">

        <div class="container">
            <div class="card my-4 shadow p-3">
                <div class="my-4">
                    @forelse ($subjects as $subject)
                        <div class="alert alert-secondary">
                            <h3>{{ $subject->title }}</h3>
                            <a class="btn" href="{{ url('/admin/questions/view') }}/{{ $subject->id }}">View All
                                ({{ $subject->Questions()->count() }})
                            </a>
                        </div>
                    @empty
                        <div class="alert alert-danger">No Subjects found</div>
                    @endforelse

                </div>
            </div>
        </div>

    </section>
@endsection
