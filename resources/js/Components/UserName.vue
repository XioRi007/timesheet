<template>
  {{
    $page.props.auth.user.roles.some(role => role.name === 'developer')
      ?
      $page.props.auth.user.developer.first_name + " " + $page.props.auth.user.developer.last_name
      :
      'admin'
  }}
</template>