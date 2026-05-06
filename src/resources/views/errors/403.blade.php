@extends('errors.layout')

@section('code', '403')
@section('label', 'Forbidden')
@section('title', 'アクセス権限がありません')
@section('message', 'このページを閲覧する権限がありません。ログインが必要な場合や、対象のロールでないとアクセスできない場合があります。')
