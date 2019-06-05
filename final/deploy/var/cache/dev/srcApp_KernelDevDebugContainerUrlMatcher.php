<?php

use Symfony\Component\Routing\Matcher\Dumper\PhpMatcherTrait;
use Symfony\Component\Routing\RequestContext;

/**
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class srcApp_KernelDevDebugContainerUrlMatcher extends Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher
{
    use PhpMatcherTrait;

    public function __construct(RequestContext $context)
    {
        $this->context = $context;
        $this->staticRoutes = [
            '/api/login_check' => [[['_route' => 'user_login_check', '_controller' => 'App\\Controller\\ApiController::loginCheckAction'], null, ['POST' => 0], null, false, false, null]],
            '/api/register' => [[['_route' => 'user_register', '_controller' => 'App\\Controller\\ApiController::registerAction'], null, ['POST' => 0], null, false, false, null]],
            '/api/session' => [[['_route' => 'user_session', '_controller' => 'App\\Controller\\ApiController::sessionRetriver'], null, ['GET' => 0], null, false, false, null]],
            '/api/test' => [[['_route' => 'test_test', '_controller' => 'App\\Controller\\ApiController::testRoute'], null, ['POST' => 0], null, false, false, null]],
            '/configuracion' => [[['_route' => 'configuracion_index', '_controller' => 'App\\Controller\\ConfiguracionController::index'], null, ['GET' => 0], null, true, false, null]],
            '/configuracion/new' => [[['_route' => 'configuracion_new', '_controller' => 'App\\Controller\\ConfiguracionController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
            '/consulta/reportes/motivo' => [[['_route' => 'reporte_consulta_motivo', '_controller' => 'App\\Controller\\ConsultaController::viewAttentionsByReason'], null, ['GET' => 0], null, false, false, null]],
            '/consulta/reportes/genero' => [[['_route' => 'reporte_consulta_genero', '_controller' => 'App\\Controller\\ConsultaController::viewAttentionsByGenre'], null, ['GET' => 0], null, false, false, null]],
            '/consulta/reportes/localidad' => [[['_route' => 'reporte_consulta_localidad', '_controller' => 'App\\Controller\\ConsultaController::viewAttentionsByLocation'], null, ['GET' => 0], null, false, false, null]],
            '/' => [[['_route' => 'default', '_controller' => 'App\\Controller\\DefaultController::index'], null, null, null, false, false, null]],
            '/paciente/index' => [[['_route' => 'paciente_index', '_controller' => 'App\\Controller\\PacienteController::index'], null, ['GET' => 0], null, false, false, null]],
            '/paciente/generos' => [[['_route' => 'paciente_generos', '_controller' => 'App\\Controller\\PacienteController::generos'], null, ['GET' => 0], null, false, false, null]],
            '/paciente/new' => [[['_route' => 'paciente_new', '_controller' => 'App\\Controller\\PacienteController::new'], null, ['POST' => 0], null, false, false, null]],
            '/role/index' => [[['_route' => 'roles_del_sistema_index', '_controller' => 'App\\Controller\\RolesDelSistemaController::index'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
            '/role/permissions_all' => [[['_route' => 'permissions_all', '_controller' => 'App\\Controller\\RolesDelSistemaController::getAppPermissions'], null, ['GET' => 0], null, false, false, null]],
            '/user/index' => [[['_route' => 'usuario_index', '_controller' => 'App\\Controller\\UserController::index'], null, ['GET' => 0], null, false, false, null]],
            '/user/new' => [[['_route' => 'usuario_new', '_controller' => 'App\\Controller\\UserController::new'], null, ['POST' => 0], null, false, false, null]],
            '/api/doc.json' => [[['_route' => 'app.swagger', '_controller' => 'nelmio_api_doc.controller.swagger'], null, ['GET' => 0], null, false, false, null]],
            '/api/doc' => [[['_route' => 'app.swagger_ui', '_controller' => 'nelmio_api_doc.controller.swagger_ui'], null, ['GET' => 0], null, false, false, null]],
        ];
        $this->regexpList = [
            0 => '{^(?'
                    .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
                    .'|/consulta/index/([^/]++)(*:66)'
                    .'|/app(.+)(*:81)'
                    .'|/paciente/([^/]++)(?'
                        .'|/edit(*:114)'
                        .'|(*:122)'
                    .')'
                    .'|/role/([^/]++)/edit(*:150)'
                    .'|/user/([^/]++)(?'
                        .'|/edit(?'
                            .'|(*:183)'
                            .'|_state(*:197)'
                        .')'
                        .'|(*:206)'
                    .')'
                .')/?$}sDu',
        ];
        $this->dynamicRoutes = [
            35 => [[['_route' => '_twig_error_test', '_controller' => 'twig.controller.preview_error::previewErrorPageAction', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
            66 => [[['_route' => 'consulta_index', '_controller' => 'App\\Controller\\ConsultaController::index'], ['id'], ['GET' => 0], null, false, true, null]],
            81 => [[['_route' => 'front_view', '_controller' => 'App\\Controller\\DefaultController::frontView'], ['token'], null, null, false, true, null]],
            114 => [[['_route' => 'paciente_edit', '_controller' => 'App\\Controller\\PacienteController::edit'], ['id'], ['POST' => 0], null, false, false, null]],
            122 => [[['_route' => 'paciente_delete', '_controller' => 'App\\Controller\\PacienteController::delete'], ['id'], ['DELETE' => 0], null, false, true, null]],
            150 => [[['_route' => 'role_edit', '_controller' => 'App\\Controller\\RolesDelSistemaController::edit'], ['id'], ['POST' => 0], null, false, false, null]],
            183 => [[['_route' => 'usuario_edit', '_controller' => 'App\\Controller\\UserController::edit'], ['id'], ['POST' => 0], null, false, false, null]],
            197 => [[['_route' => 'usuario_edit_state', '_controller' => 'App\\Controller\\UserController::editState'], ['id'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
            206 => [[['_route' => 'usuario_delete', '_controller' => 'App\\Controller\\UserController::delete'], ['id'], ['DELETE' => 0], null, false, true, null]],
        ];
    }
}
