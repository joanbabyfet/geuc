/**
 * 谷歌地图API
 */
function cls_google_map(args) {
    //传参处理
    var {
        center_point = null, zoom = 13, mapTypeId = 'roadmap', mapTypeControl = true, address_callback = null
    } = args;
    this.center_point = center_point;
    this.map = new google.maps.Map(document.getElementById('map'), {
        center: center_point,
        zoom: zoom,
        mapTypeId: mapTypeId,
        mapTypeControl: mapTypeControl //是否显示卫星地图标签
    });
    return this;
}
cls_google_map.prototype = {
    //初始化地图
    constructor: cls_google_map,
    __CLASS__: 'cls_google_map >> ',
    map: null,
    center_point: {},
    markers: {},
    currentId: 0,
    directionsService: null,
    directionsDisplay: null,
    start_icon: 'static/img/start.svg',
    pass_icon: 'static/img/pass.svg',
    end_icon: 'static/img/end.svg',
    router: 'static/img/router.svg',

    uniqueId: function () {
        return ++this.currentId;
    },

    setCenter: function (lat, lng) {
        this.map.setCenter({
            lat: lat,
            lng: lng
        });
    },

    setZoom: function (zoom) {
        this.map.setZoom(zoom);
    },

    //信息Dialog
    infoWindow: function () {
        return new google.maps.InfoWindow({
            size: new google.maps.Size(150, 50)
        });
    },

    dragstart: function (callback) {
        var self = this;
        google.maps.event.addListener(this.map, 'dragstart', function (event) {
            var latLng = self.map.getCenter();
            var point = {
                'lat': latLng.lat(),
                'lng': latLng.lng()
            };
            callback(point);
        });
    },

    drag: function (callback) {
        var self = this;
        google.maps.event.addListener(this.map, 'drag', function (event) {
            var latLng = self.map.getCenter();
            var point = {
                'lat': latLng.lat(),
                'lng': latLng.lng()
            };
            callback(point);
        });
    },

    dragend: function (callback) {
        var self = this;
        google.maps.event.addListener(this.map, 'dragend', function (event) {
            var latLng = self.map.getCenter();
            var point = {
                'lat': latLng.lat(),
                'lng': latLng.lng()
            };
            callback(point);
        });
    },

    center_changed: function (callback) {
        var self = this;
        google.maps.event.addListener(this.map, 'center_changed', function (event) {
            var latLng = self.map.getCenter();
            var point = {
                'lat': latLng.lat(),
                'lng': latLng.lng()
            };
            callback(point);
        });
    },

    zoom: function (callback) {
        var self = this;
        google.maps.event.addListener(this.map, 'zoom_changed', function (event) {
            var value = self.map.getZoom();
            callback(value);
        });
    },

    //Event 点击地图
    click: function (callback) {
        google.maps.event.addListener(this.map, 'click', function (event) {
            var lat = event.latLng.lat();
            var lng = event.latLng.lng();
            event.point = {
                'lat': lat,
                'lng': lng
            };
            callback(event);
        });
    },

    getAddress: function (point, callback, placeId) {
        console.groupCollapsed(this.__CLASS__ + 'getAddress() 根据坐标获得地址');
        geo = new google.maps.Geocoder();
        // 传入拖拽结束点的地址坐标对象
        var obj = {};
        if (point) obj.location = point;
        if (placeId) obj.placeId = placeId;
        geo.geocode(obj, function (results, status) {
            console.groupCollapsed('>> geocode 传入拖拽结束点的地址坐标对象');
            console.log(results);
            if (status == google.maps.GeocoderStatus.OK) {
                var pointArea = {};
                var geometry = results[0].geometry;
                var address_components = results[0].address_components;
                var place_id = results[0].place_id;
                if (address_components.length >= 4) {
                    pointArea.street = address_components[0].long_name;
                    pointArea.district = address_components[1].long_name;
                    pointArea.city = address_components[2].long_name;
                    pointArea.province = address_components[3].long_name;
                    var address = pointArea.province + ' ' + pointArea.city + ' ' + pointArea.district + ' ' + pointArea.street;
                    console.log('address: ' + address);
                    callback(address, place_id);
                } else {
                    callback(null);
                }
            }
            console.groupEnd();
        });
        console.groupEnd();
    },

    //event 搜索框输入时
    change_search: function (id, callback) {
        var map = this.map;
        var _this = this;
        // Create the search box and link it to the UI element.
        var input = document.getElementById(id);
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT]; //.push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function () {
            searchBox.setBounds(map.getBounds());
        });

        //监听地址改变并搜索坐标
        searchBox.addListener('places_changed', function () {
            console.groupCollapsed(_this.__CLASS__ + 'places_changed 搜索坐标:');
            var places = searchBox.getPlaces();
            if (places.length == 0) {
                console.warn('不存在的地址，没有找到对应的坐标');
                console.groupEnd();
                return;
            } else if (places.length > 1) {
                places = [places[0]]; //当地址取到的坐标不止一个时，只取第一个坐标用于地图上的标记
            }

            // For each place, get the icon, name and location.
            var bounds = new google.maps.LatLngBounds();
            console.log('bounds:', bounds, '\n');
            places.forEach(function (place) {
                if (!place.geometry) {
                    console.warn("Returned place contains no geometry");
                    console.groupEnd();
                    return;
                }

                var lat = place.geometry.location.lat();
                var lng = place.geometry.location.lng();
                var res = {
                    id: id,
                    place_id: place.place_id,
                    address: input.value,
                    point: {
                        'lat': lat,
                        'lng': lng
                    }
                };
                //添加标记
                //_this.addMarker({ point: place.geometry.location });
                console.log('res:', res, '\n');
                !!callback && callback(res);

                if (place.geometry.viewport) {
                    //Only geometry have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });
            map.fitBounds(bounds);
            console.groupEnd();
        });
    },

    //添加地图上的标记
    addMarker: function (args) {
        console.groupCollapsed(this.__CLASS__ + 'addMarker() 添加地图上的标记:');
        var {
            point = null, name = null, contentString = '', address_callback = null, is_cancel = false, is_get_address = false
        } = args;
        var _this = this;
        console.log('point:', point, '\n');

        //创建一个标签对象
        var id = this.uniqueId(); // get new id
        var marker = new google.maps.Marker({
            id: id,
            position: point,
            //geo : geo,
            map: this.map,
            draggable: true,
            animation: google.maps.Animation.DROP //标签落点的动画
        });

        if (is_get_address) {
            this.getAddress(point, address_callback);
        }

        var _infowindow = _this.infoWindow();
        //添加标记点击监听事件
        google.maps.event.addListener(marker, 'click', function () {
            //googleMap.infowindow.setPosition(this.position);
            //!!contentString && googleMap.infowindow.setContent(contentString);
            //googleMap.infowindow.open(_this.map, marker);
            !!contentString && _infowindow.setContent(contentString);
            !!contentString && _infowindow.open(_this.map, marker);
        });

        //将之前标记的标记删除，只保留当前的标记
        this.markers[id] = {
            id: id,
            marker: marker,
            position: point
        };
        var markers = this.markers;
        for (var i in markers) {
            if (i != id) {
                _this.delMarker(i);
            }
        }

        //右键取消地图标记
        if (is_cancel) {
            google.maps.event.addListener(marker, "rightclick", function (point) {
                _this.delMarker(this.id)
            });
        }
        console.groupEnd();
    },

    //添加地图路线的标记
    addMarkerRoute: function (args) {
        console.groupCollapsed(this.__CLASS__ + 'addMarkerRoute() 添加地图路线的标记:');
        var {
            point = null, name = null, contentString = '', address_callback = null, is_cancel = false, is_get_address = false
        } = args;
        var _this = this;

        console.log('point:', point, '\n');
        //创建一个标签对象
        var label_texts = {
            'search_start': '起',
            'search_end_0': '终',
            'search_end_1': '终'
        };
        var text = label_texts[name];
        var id = name; // get new id
        var _icon = "";
        if (id == 'search_start') {
            _icon = _this.start_icon;
        } else if (id == 'search_end_0') {
            //_icon = _this.pass_icon;
            _icon = _this.end_icon;
        } else if (id == 'search_end_1') {
            _icon = _this.end_icon;
        }
        var marker = new google.maps.Marker({
            id: id,
            position: point,
            // label: {
            //     'color': '#fff',
            //     'text': text
            // },
            map: this.map,
            draggable: true,
            animation: google.maps.Animation.DROP, //标签落点的动画
            icon: _icon
            //icon: customIcon({ fillColor: '#2ecc71' })
        });
        console.log(marker);

        if (is_get_address) {
            this.getAddress(point, address_callback);
        }

        var _infowindow = _this.infoWindow();
        //添加标记点击监听事件
        google.maps.event.addListener(marker, 'click', function () {
            //googleMap.infowindow.setPosition(this.position);
            //!!contentString && googleMap.infowindow.setContent(contentString);
            //googleMap.infowindow.open(_this.map, marker);
            !!contentString && _infowindow.setContent(contentString);
            !!contentString && _infowindow.open(_this.map, marker);
        });

        //将之前标记的标记删除，只保留当前的标记
        var markers = this.markers;
        for (var i in markers) {
            if (i == id) {
                this.delMarker(i);
            }
        }
        this.markers[id] = {
            id: id,
            marker: marker,
            position: point
        };

        //右键取消地图标记
        if (is_cancel) {
            google.maps.event.addListener(marker, "rightclick", function (point) {
                _this.delMarker(this.id)
            });
        }

        if (markers.hasOwnProperty('search_start') && markers.hasOwnProperty('search_end_0')) {
            var start_point = {
                lat: markers.search_start.marker.position.lat(),
                lng: markers.search_start.marker.position.lng()
            };
            var end_point = {
                lat: markers.search_end_0.marker.position.lat(),
                lng: markers.search_end_0.marker.position.lng()
            };
            var route_config = {
                start_point: start_point,
                end_point: end_point
            };

            //两个终点时,调换原来的终点为停车点
            if (markers.hasOwnProperty('search_end_1')) {
                _this.changeMarker(markers, _this.pass_icon);
                route_config.stop_point = route_config.end_point;
                route_config.end_point = {
                    lat: markers.search_end_1.marker.position.lat(),
                    lng: markers.search_end_1.marker.position.lng()
                };
            } else {
                _this.changeMarker(markers, _this.end_icon);
            }
            console.log('route_config', route_config);
            this.route(route_config)
        }
        console.groupEnd();
    },

    //路线规划
    route: function (args) {
        console.groupCollapsed(this.__CLASS__ + 'route() 生成路线规划');
        //传参处理
        var {
            start_point = null, end_point = null, stop_point = null, show_marker = false
        } = args;
        var _this = this;
        if (_this.directionsService == null) {
            _this.directionsService = new google.maps.DirectionsService();
            _this.directionsDisplay = new google.maps.DirectionsRenderer({
                suppressMarkers: !show_marker
            });
        }

        _this.directionsDisplay.setMap(_this.map);
        start_point = start_point ? start_point : this.center_point;
        //开始规划路线
        var request = {
            origin: start_point, //出发point
            destination: end_point, //结束point
            travelMode: google.maps.TravelMode['DRIVING'] //规划模式 行走,自驾车...
        };

        //多个终点时，第一个停车点
        if (stop_point) {
            request.waypoints = [{
                location: stop_point,
                stopover: true
            }];
        } else {
            _this.changeMarker(_this.markers, _this.end_icon);
        }

        _this.directionsService.route(request, function (response, status) {
            if (status == 'OK') {
                _this.directionsDisplay.setDirections(response);
            }
        });
        console.groupEnd();
    },

    //改变标签显示
    changeMarker: function (markers, _icon) {
        var _this = this;
        var _id = markers.search_end_0.id;
        var _point = markers.search_end_0.position;
        var _marker = new google.maps.Marker({
            id: _id,
            position: _point,
            map: _this.map,
            draggable: true,
            animation: google.maps.Animation.DROP, //标签落点的动画
            icon: _icon
        });
        _this.delMarker('search_end_0');
        _this.markers['search_end_0'] = {
            id: _id,
            marker: _marker,
            position: _point
        };
    },

    //删除标记
    delMarker: function (id) {
        if (this.markers[id] != undefined) {
            this.markers[id].marker.setMap(null);
            delete this.markers[id];
        }
    }
}

function customIcon(opts) {
    return Object.assign({
        path: 'M 0,0 C -2,-20 -10,-22 -10,-30 A 10,10 0 1,1 10,-30 C 10,-22 2,-20 0,0 z M -2,-30 a 2,2 0 1,1 4,0 2,2 0 1,1 -4,0',
        fillColor: '#fff',
        fillOpacity: 1,
        strokeColor: '#000',
        strokeWeight: 2,
        scale: 1,
    }, opts);
}